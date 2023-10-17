<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Parser\Client;

use Cake\Http\Response;

/**
 * Http response from Wildberries API.
 */
class WbSearchResponse
{
    public function __construct(private string $content)
    {
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
