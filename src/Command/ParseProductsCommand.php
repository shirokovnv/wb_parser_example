<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\Stages\Pipeline;
use App\Command\Stages\StageConversion;
use App\Command\Stages\StageInsertion;
use App\Command\Stages\StagePagination;
use App\Command\Stages\StageParsing;
use App\Service\WbProducts\Converter\WbProductsConverterInterface;
use App\Service\WbProducts\Exception\WbProductsExceptionHandler;
use App\Service\WbProducts\Parser\WbProductsParserInterface;
use App\Service\WbProducts\Repository\WbProductsRepositoryInterface;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * Зона отвественности: Парсинг товаров по поисковой фразе с search.wb.ru и сохранение их в ClickHouse (первые 1000 штук).
 */
class ParseProductsCommand extends AbstractClickhouseCommand
{
    private const KEY_QUERY = 'query';

    /**
     * @param WbProductsParserInterface $parser
     * @param WbProductsConverterInterface $converter
     * @param WbProductsRepositoryInterface $repository
     * @param WbProductsExceptionHandler $exceptionHandler
     */
    public function __construct(
        private WbProductsParserInterface $parser,
        private WbProductsConverterInterface $converter,
        private WbProductsRepositoryInterface $repository,
        private WbProductsExceptionHandler $exceptionHandler
    ) {
    }

    /**
     * @param ConsoleOptionParser $parser
     * @return ConsoleOptionParser
     */
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->addArgument(self::KEY_QUERY, [
            'help' => 'Query string for product search in Wildberries'
        ]);
        return $parser;
    }

    /**
     * @param Arguments $args
     * @param ConsoleIo $io
     * @return int|void|null
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $userQuery = trim((string) $args->getArgument(self::KEY_QUERY));

        if (! $this->validateUserInput($userQuery)) {
            $io->error(
                'Строка должна быть не пустой и может содержать только буквы латиницы или кириллицы, цифры и пробелы.'
            );

            return self::CODE_ERROR;
        }

        try {
            // 1. Разбиение на страницы 1..10
            // 2. Парсинг
            // 3. Конвертация данных
            // 4. Вставка 1000 записей

            $pipeline = (new Stages\Pipeline((new StagePagination())()))
                ->pipe((new StageParsing($this->parser, $userQuery)))
                ->pipe((new StageConversion($this->converter, $io)))
                ->pipe((new StageInsertion($this->repository, $io)))
                ->tap();

            Pipeline::iterate($pipeline);

        } catch (\Throwable $exception) {
            $userFriendlyException = $this->exceptionHandler->handle($exception);

            $io->error($userFriendlyException->getMessage());

            return self::CODE_ERROR;
        }

        return self::CODE_SUCCESS;
    }

    /**
     * @param string $userQuery
     * @return bool
     */
    private function validateUserInput(string $userQuery): bool
    {
        // Латиница, кириллица, цифры и пробелы
        return (bool) preg_match("/^([0-9a-zA-Zа-яёЁА-Я ]+)$/iu", $userQuery);
    }
}
