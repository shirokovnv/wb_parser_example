<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\WbProducts\Converter\Exception\ConvertException;
use App\Service\WbProducts\Converter\WbProductsConverterInterface;
use App\Service\WbProducts\Parser\WbProductsParserInterface;
use App\Service\WbProducts\Repository\WbProductsRepositoryInterface;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Psr\Http\Client\ClientExceptionInterface;

class ParseProductsCommand extends AbstractClickhouseCommand
{
    private const KEY_QUERY = 'query';

    /**
     * @param WbProductsParserInterface $parser
     * @param WbProductsConverterInterface $converter
     * @param WbProductsRepositoryInterface $repository
     */
    public function __construct(
        private WbProductsParserInterface $parser,
        private WbProductsConverterInterface $converter,
        private WbProductsRepositoryInterface $repository
    )
    {
    }

    /**
     * @param ConsoleOptionParser $parser
     * @return ConsoleOptionParser
     */
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->addArgument(self::KEY_QUERY, [
            'help' => 'Query string for products search in Wildberries'
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
        $userQuery = $args->getArgument(self::KEY_QUERY);

        if ($userQuery === null) {
            $io->error('Query string is required.');

            return self::CODE_ERROR;
        }

        try {
            $response = $this->parser->parseByQueryString($userQuery);
            $products = $this->converter->convert($response->getContent(), $userQuery);
            $this->repository->bulkInsert($products);
        } catch (\Throwable $exception) {
            if ($exception instanceof ClientExceptionInterface) {
                // TODO: log http error ? Define retry policy ? ...
            }

            if ($exception instanceof ConvertException) {
                // TODO: log converter error
            }

            // TODO: do something else ?

            $io->error($exception->getMessage());

            return self::CODE_ERROR;
        }

        $io->out(sprintf("Inserted %d products\r\n", count($products)));

        return self::CODE_SUCCESS;
    }
}
