<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Parser;

use App\Service\WbProducts\Parser\Client\WbSearchResponse;

interface WbProductsParserInterface
{
    /**
     * @param string $query
     * @return WbSearchResponse
     */
    public function parseByQueryString(string $query): WbSearchResponse;
}
