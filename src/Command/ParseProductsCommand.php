<?php

declare(strict_types=1);

namespace App\Command;

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

    private const MIN_PAGE = 1;

    private const MAX_PAGE = 10;

    private const PER_PAGE = 100;

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

        if (! $this->ensureUserInputIsValid($userQuery)) {
            $io->error(
                'Строка должна быть не пустой и может содержать только буквы латиницы или кириллицы, цифры и пробелы.'
            );

            return self::CODE_ERROR;
        }

        $products = [];

        try {
            // TODO: Здесь мы последовательно запрашиваем 10 страниц с внешнего источника.
            // Подход: все или ничего, либо получаем всю 1000 товаров и записываем их, либо нет.
            // Возможной оптимизацией является использование внешней зависимости Guzzle/Promises с асинхронным вызовом.
            for ($page = self::MIN_PAGE; $page <= self::MAX_PAGE; $page++) {
                $response = $this->parser->parseByUserParams($userQuery, $page);

                $products = array_merge(
                    $products,
                    $this->converter->convert($response->getContent(), ($page - 1) * self::PER_PAGE)
                );

                $io->info(sprintf("Collect %d products", count($products)));
            }

            $this->repository->bulkInsert($products);
        } catch (\Throwable $exception) {
            $userFriendlyException = $this->exceptionHandler->handle($exception);

            $io->error($userFriendlyException->getMessage());

            return self::CODE_ERROR;
        }

        if (count($products) > 0) {
            $io->out(sprintf("Inserted %d products\r\n", count($products)));
        } else {
            $io->out('No products inserted...');
        }

        return self::CODE_SUCCESS;
    }

    /**
     * @param string $userQuery
     * @return bool
     */
    private function ensureUserInputIsValid(string $userQuery): bool
    {
        // Латиница, кириллица, цифры и пробелы
        return (bool) preg_match("/^([0-9a-zA-Zа-яёЁА-Я ]+)$/iu", $userQuery);
    }
}
