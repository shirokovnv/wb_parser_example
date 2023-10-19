<?php

declare(strict_types=1);

namespace App\Test\TestCase\Service\WbProducts\Parser\Factory;

use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;

/**
 * Фабрика для Http-ответов от search.wb.ru.
 */
class WbSearchResponseFactory
{
    /**
     * @param string $userQuery
     * @param int $numProducts
     *
     * @return ResponseInterface
     */
    public static function createSuccessfulResponse(string $userQuery, int $numProducts = 100): ResponseInterface
    {
        if ($numProducts <= 0) {
            throw new \LogicException('Number of products must be positive integer.');
        }

        $products = [];
        for ($i = 0; $i < $numProducts; $i++) {
            $products[] = [
                'name' => sprintf('Item %d', $i),
                'brand' => sprintf('Brand %d', $i)
            ];
        }

        $dataFixture = [
            'metadata' => [
                'name' => $userQuery
            ],
            'state' => 0,
            'version' => 2,
            'data' => [
                'products' => $products
            ]
        ];

        return self::createResponseWithBodyAndStatus(json_encode($dataFixture, JSON_PRETTY_PRINT), 200);
    }

    /**
     * @return ResponseInterface
     */
    public static function createEmptyResponse(): ResponseInterface
    {
        return self::createResponseWithBodyAndStatus('{}', 200);
    }

    /**
     * @param string $body
     * @param int $status
     *
     * @return ResponseInterface
     */
    private static function createResponseWithBodyAndStatus(string $body, int $status): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($body);
        $response->getBody()->rewind();

        return $response->withStatus($status);
    }
}
