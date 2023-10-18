<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Repository;

use App\Model\Table\WbProductsClickhouseTable;
use App\Service\WbProducts\DTO\WbProductEntity;
use Eggheads\CakephpClickHouse\Exception\FieldNotFoundException;

class WbProductsRepository implements WbProductsRepositoryInterface
{
    /**
     * @var WbProductsClickhouseTable
     */
    private WbProductsClickhouseTable $table;

    public function __construct()
    {
        // TODO: bad practise. May be it's better to use direct inserts from clickhouse client
        $this->table = WbProductsClickhouseTable::getInstance();
    }

    /**
     * @param array<WbProductEntity> $products
     * @return void
     *
     * @throws FieldNotFoundException
     */
    public function bulkInsert(array $products): void
    {
        $this->table->transactionalInsert($products);
    }

    /**
     * @param string $query
     * @return array<WbProductEntity>
     */
    public function getByQueryString(string $query): array
    {
        $rows = $this->table
            ->select('SELECT * FROM wbProducts WHERE query = :query', ['query' => $query])
            ->rows();

        return array_map(
            fn (array $row) => new WbProductEntity($row['name'], $row['brand'], $row['position'], $row['query']),
            $rows
        );
    }
}
