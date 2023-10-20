<?php

declare(strict_types=1);

namespace App\Test\Mocks\WbProductsEndpoint\Providers;

use App\Model\Table\WbProductsTableInterface;
use App\Service\WbProducts\Repository\WbProductsRepository;
use App\Service\WbProducts\Repository\WbProductsRepositoryInterface;

class WbProductsRepositoryProvider
{
    /**
     * @param WbProductsTableInterface $table
     * @return WbProductsRepositoryInterface
     */
    public static function getInstance(WbProductsTableInterface $table): WbProductsRepositoryInterface
    {
        return new WbProductsRepository($table);
    }
}
