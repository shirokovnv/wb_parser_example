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

    private const MIN_PAGE = 1;

    private const MAX_PAGE = 10;

    private const PER_PAGE = 100;

    /**
     * @param WbProductsParserInterface $parser
     * @param WbProductsConverterInterface $converter
     * @param WbProductsRepositoryInterface $repository
     */
    public function __construct(
        private WbProductsParserInterface $parser,
        private WbProductsConverterInterface $converter,
        private WbProductsRepositoryInterface $repository
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
        $userQuery = $args->getArgument(self::KEY_QUERY);

        if ($userQuery === null) {
            $io->error('Query string is required.');

            return self::CODE_ERROR;
        }

        $products = [];

        try {
            for ($page = self::MIN_PAGE; $page <= self::MAX_PAGE; $page++) {
                $response = $this->parser->parseByUserParams($userQuery, $page);

                // TODO: product keys must be numeric to avoid overwriting.
                $products = array_merge(
                    $products,
                    $this->converter->convert($response->getContent(), ($page - 1) * self::PER_PAGE)
                );

                $io->info(sprintf("Collect %d products", count($products)));
            }

            $this->repository->bulkInsert($products);
        } catch (\Throwable $exception) {
            if ($exception instanceof ClientExceptionInterface) {
                $this->log('Http client exception: ' . $exception->getMessage());
            }

            if ($exception instanceof ConvertException) {
                $this->log('Converter exception: ' . $exception->getMessage());
            }

            $io->error($exception->getMessage());

            return self::CODE_ERROR;
        }

        if (count($products) > 0) {
            $io->out(sprintf("Inserted %d products\r\n", count($products)));
        } else {
            $io->out('No products inserted...');
        }

        return self::CODE_SUCCESS;
    }
}
