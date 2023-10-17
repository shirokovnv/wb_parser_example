<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Parser\Client;

use App\Service\WbProducts\DTO\WbProductEntity;

class WbSearchResponse
{
    private const KEY_DATA = 'data';

    private const KEY_PRODUCTS = 'products';

    private const KEY_META = 'metadata';

    private const KEY_QUERY = 'normquery';

    /**
     * @var array<WbProductEntity>
     */
    private array $products;

    /**
     * @param array $products
     */
    private function __construct(array $products)
    {
        $this->products = $products;
    }

    /**
     * @param array $json
     * @return WbSearchResponse
     * @throws \Exception
     */
    public static function fromJSON(array $json): WbSearchResponse
    {
        return new WbSearchResponse(self::parseJson($json));
    }

    /**
     * @return WbSearchResponse
     */
    public static function empty(): WbSearchResponse
    {
        return new WbSearchResponse([]);
    }

    /**
     * @return array<WbProductEntity>
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param array $json
     * @return array<WbProductEntity>
     *
     * @throws \Exception
     *
     * TODO: may be not a responsibility of this class, move to service instead?
     */
    private static function parseJson(array $json): array
    {
        self::assertJsonStructure($json);

        $products = $json[self::KEY_DATA][self::KEY_PRODUCTS];

        // TODO: use original query ?
        $query = $json[self::KEY_META][self::KEY_QUERY] ?? '';

        // TODO: Starting index = 0 OR 1 ?
        return array_map(
            fn (array $product, int $index) => new WbProductEntity(
                $product['name'],
                $product['brand'],
                $index + 1,
                $query
            ),
            $products,
            array_keys($products)
        );
    }

    /**
     * @param array $responseData
     * @return void
     * @throws \Exception
     */
    private static function assertJsonStructure(array $responseData): void
    {
        // TODO: we interested only in 'products' key, but can assert also: metadata, version, state...
        if (! isset($responseData[self::KEY_DATA][self::KEY_PRODUCTS])) {
            throw new \Exception('Bad response structure');
        }
    }
}
