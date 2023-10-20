<?php

declare(strict_types=1);

namespace App\Test\Mocks\WbProductsEndpoint\Providers;

use App\Service\WbProducts\Parser\WbProductsParser;
use App\Service\WbProducts\Parser\WbProductsParserInterface;
use Psr\Http\Client\ClientInterface;

class WbProductsParserProvider
{
    /**
     * @param ClientInterface $client
     * @return WbProductsParserInterface
     */
    public static function getInstance(ClientInterface $client): WbProductsParserInterface
    {
        return new WbProductsParser($client);
    }
}
