<?php

declare(strict_types=1);

namespace App\Test\Mocks\WbProductsEndpoint\Providers;

use App\Service\WbProducts\Converter\WbProductsConverterInterface;
use App\Service\WbProducts\Converter\WbProductsSearchConverter;

class WbProductsConverterProvider
{
    /**
     * @return WbProductsConverterInterface
     */
    public static function getInstance(): WbProductsConverterInterface
    {
        return new WbProductsSearchConverter();
    }
}
