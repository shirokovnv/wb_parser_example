<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Parser;

use App\Service\WbProducts\Parser\Client\WbSearchRequest;
use App\Service\WbProducts\Parser\Client\WbSearchResponse;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class WbProductsParser implements WbProductsParserInterface
{
    /**
     * @param ClientInterface $client
     */
    public function __construct(private ClientInterface $client)
    {
    }

    /**
     * @param string $query
     * @param int $page
     *
     * @return WbSearchResponse
     * @throws ClientExceptionInterface
     */
    public function parseByUserParams(string $query, int $page): WbSearchResponse
    {
        $request = WbSearchRequest::fromUserParams($query, $page);
        $response = $this->client->sendRequest($request);

        return new WbSearchResponse($response->getBody()->getContents());
    }
}
