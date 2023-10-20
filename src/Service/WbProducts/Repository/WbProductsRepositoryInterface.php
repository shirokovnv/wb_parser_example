<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Repository;

use App\Service\WbProducts\Entity\Product;

interface WbProductsRepositoryInterface
{
    /**
     * @param array<Product> $products
     * @return void
     */
    public function bulkInsert(array $products): void;

    /**
     * @param string $query
     * @param int $limit
     * @param int $offset
     * @return array<Product>
     */
    public function getByQueryString(string $query, int $limit = 1000, int $offset = 0): array;
}
