<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Repository;

use App\Model\Table\WbProductsTableInterface;
use App\Service\WbProducts\Entity\Product;
use ClickHouseDB\Exception\QueryException;
use Eggheads\CakephpClickHouse\Exception\FieldNotFoundException;

/**
 * Зона ответственности: сохранение и получение данных о товарах из ClickHouse.
 */
class WbProductsRepository implements WbProductsRepositoryInterface
{
    /**
     * @param WbProductsTableInterface $table
     */
    public function __construct(private WbProductsTableInterface $table)
    {
    }

    /**
     * @param array<Product> $products
     * @return void
     *
     * @throws FieldNotFoundException|QueryException
     */
    public function bulkInsert(array $products): void
    {
        $this->table->bulkInsert($products);
    }

    /**
     * @param string $query
     * @param int $limit
     * @param int $offset
     * @return array<Product>
     */
    public function getByQueryString(string $query, int $limit = 1000, int $offset = 0): array
    {
        $rows = $this->table
            ->select(
                'SELECT `name`, `brand`, `position`, `query` FROM wbProducts WHERE query = :query LIMIT :limit OFFSET :offset',
                [
                    'query' => $query,
                    'limit' => $limit,
                    'offset' => $offset,
                ]
            )
            ->rows();

        return array_map(
            fn (array $row) => new Product($row['name'], $row['brand'], $row['position'], $row['query']),
            $rows
        );
    }
}
