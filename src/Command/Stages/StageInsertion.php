<?php

declare(strict_types=1);

namespace App\Command\Stages;

use App\Service\WbProducts\Repository\WbProductsRepositoryInterface;
use Cake\Console\ConsoleIo;

/**
 * Стадия вставки записей в ClickHouse.
 */
class StageInsertion implements StageInterface
{
    /**
     * @param WbProductsRepositoryInterface $repository
     * @param ConsoleIo $io
     */
    public function __construct(private WbProductsRepositoryInterface $repository, private ConsoleIo $io)
    {
    }

    /**
     * @param array|\Generator $flow
     * @return \Generator
     */
    public function __invoke(array|\Generator $flow): \Generator
    {
        foreach($flow as $products) {
            if (count($products) > 0) {
                $this->repository->bulkInsert($products);
                $this->io->out(sprintf("Inserted %d products\r\n", count($products)));
            }

            yield $products;
        }
    }
}
