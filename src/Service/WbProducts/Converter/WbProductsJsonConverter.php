<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Converter;

use App\Service\WbProducts\Converter\Exception\ConvertException;
use App\Service\WbProducts\DTO\WbProductEntity;

class WbProductsJsonConverter implements WbProductsConverterInterface
{
    private const KEY_DATA = 'data';

    private const KEY_PRODUCTS = 'products';

    private const KEY_META = 'metadata';

    private const KEY_QUERY = 'normquery';

    /**
     * @param mixed $rawData
     * @param string $userQuery
     * @return array<WbProductEntity>
     *
     * @throws \Exception
     */
    public function convert(mixed $rawData, string $userQuery): array
    {
        $this->assertRawDataIsString($rawData);

        $jsonData = json_decode($rawData, true, JSON_THROW_ON_ERROR);

        $this->assertJsonStructure($jsonData);

        $products = $jsonData[self::KEY_DATA][self::KEY_PRODUCTS];

        // TODO: use normalized query or original query ?
        $query = $json[self::KEY_META][self::KEY_QUERY] ?? $userQuery;

        // TODO: Starting index = 0 OR 1 ?
        return array_map(
            fn (array $product, int $index) => new WbProductEntity(
                $product['name'],
                $product['brand'],
                $index + 1,
                $userQuery
            ),
            $products,
            array_keys($products)
        );
    }

    /**
     * @param mixed $rawData
     * @return void
     * @throws \Exception
     */
    private function assertRawDataIsString(mixed $rawData) {
        if (! is_string($rawData)) {
            throw new ConvertException('Raw data must be string. Unable to parse JSON.');
        }
    }

    /**
     * @param array $jsonData
     * @return void
     * @throws \Exception
     */
    private function assertJsonStructure(array $jsonData) {
        if (! isset($jsonData[self::KEY_DATA][self::KEY_PRODUCTS])) {
            throw new \Exception('Bad JSON structure');
        }
    }
}
