<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Parser;

use App\Service\WbProducts\Parser\Client\WbSearchResponse;

interface WbProductsParserInterface
{
    /**
     * @param string $query
     * @param int $page
     *
     * @return WbSearchResponse
     */
    public function parseByUserParams(string $query, int $page): WbSearchResponse;
}
