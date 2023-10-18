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

    private const KEY_NAME = 'name';

    /**
     * @param mixed $rawData
     * @param int $startIndex
     * @return array<WbProductEntity>
     *
     * @throws ConvertException
     */
    public function convert(mixed $rawData, int $startIndex = 1): array
    {
        $this->assertRawDataIsString($rawData);

        $jsonData = json_decode($rawData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ConvertException(json_last_error_msg());
        }

        $this->assertProductsKeyContainsArray($jsonData);
        $this->assertMetaKeyContainsQueryString($jsonData);

        $products = $jsonData[self::KEY_DATA][self::KEY_PRODUCTS];
        $query = (string) $jsonData[self::KEY_META][self::KEY_NAME];

        // TODO: what if fields are nullable ? OR we don't find field keys ?
        return array_map(
            fn (array $product, int $index) => new WbProductEntity(
                $product['name'],
                $product['brand'],
                $startIndex + $index + 1,
                $query
            ),
            $products,
            array_keys($products)
        );
    }

    /**
     * @param mixed $rawData
     * @return void
     *
     * @throws ConvertException
     */
    private function assertRawDataIsString(mixed $rawData) {
        if (! is_string($rawData)) {
            throw new ConvertException('Raw data must be string. Unable to parse JSON.');
        }
    }

    /**
     * @param array $jsonData
     * @return void
     *
     * @throws ConvertException
     */
    private function assertProductsKeyContainsArray(array $jsonData) {
        if (! (isset($jsonData[self::KEY_DATA][self::KEY_PRODUCTS]) &&
            is_array($jsonData[self::KEY_DATA][self::KEY_PRODUCTS]))) {
            throw new ConvertException('Products key must contain array.');
        }
    }

    /**
     * @param array $jsonData
     * @return void
     *
     * @throws ConvertException
     */
    private function assertMetaKeyContainsQueryString(array $jsonData) {
        if (! (isset($jsonData[self::KEY_META][self::KEY_NAME])
            && is_string($jsonData[self::KEY_META][self::KEY_NAME]))) {
            throw new ConvertException('Meta key must provide query string.');
        }
    }
}
