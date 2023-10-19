<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Repository;

use App\Service\WbProducts\DTO\WbProductEntity;

interface WbProductsRepositoryInterface
{
    /**
     * @param array<WbProductEntity> $products
     * @return void
     */
    public function bulkInsert(array $products): void;

    /**
     * @param string $query
     * @param int $limit
     * @param int $offset
     * @return array<WbProductEntity>
     */
    public function getByQueryString(string $query, int $limit = 1000, int $offset): array;
}
