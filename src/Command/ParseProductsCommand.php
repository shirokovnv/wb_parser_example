<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\WbProducts\Parser\WbProductsParserInterface;
use App\Service\WbProducts\Repository\WbProductsRepositoryInterface;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class ParseProductsCommand extends AbstractClickhouseCommand
{
    private const KEY_QUERY = 'query';

    /**
     * @param WbProductsParserInterface $parser
     * @param WbProductsRepositoryInterface $repository
     */
    public function __construct(
        private WbProductsParserInterface $parser,
        private WbProductsRepositoryInterface $repository)
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
        $response = $this->parser->parseByQueryString($args->getArgument(self::KEY_QUERY));
        $products = $response->getProducts();
        $this->repository->transactionalBulkInsert($products);

        $io->out(sprintf("Inserted %d products\r\n", count($products)));

        return self::CODE_SUCCESS;
    }
}
