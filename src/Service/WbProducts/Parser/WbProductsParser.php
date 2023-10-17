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
     * @return WbSearchResponse
     *
     * @throws \Exception
     */
    public function parseByQueryString(string $query): WbSearchResponse
    {
        try {
            $response = $this->client->sendRequest(WbSearchRequest::fromQueryString($query));
            $json = json_decode($response->getBody()->getContents(), true, JSON_THROW_ON_ERROR);

            $result = WbSearchResponse::fromJSON($json);

        } catch (\Throwable $exception) {

            if ($exception instanceof ClientExceptionInterface) {
                // TODO: log exception, define retry policy, etc...
            }

            if ($exception instanceof \JsonException) {
                // TODO: log base json exception
            }

            // TODO: otherwise do something ?

            $result = WbSearchResponse::empty();
        }

        return $result;
    }
}
