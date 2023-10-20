<?php

declare(strict_types=1);

namespace App\Test\TestCase\Service\WbProducts\Converter;

use App\Service\WbProducts\Converter\Exception\ConvertException;
use App\Service\WbProducts\Entity\Product;
use App\Test\Mocks\WbProductsEndpoint\Providers\WbProductsConverterProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\WbProducts\Converter\WbProductsSearchConverter
 *
 * @group service
 * @group wbProducts
 */
class WbProductsSearchConverterTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @param string $data
     *
     * @return void
     */
    public function testConversionSuccessful(string $data): void
    {
        $converter = WbProductsConverterProvider::getInstance();

        $productArr = $converter->convert($data);
        $this->assertIsArray($productArr);

        foreach ($productArr as $productEntity) {
            $this->assertInstanceOf(Product::class, $productEntity);
        }
    }

    /**
     * @dataProvider wrongDataProvider
     *
     * @param string|null $data
     *
     * @return void
     */
    public function testConversionErrors(?string $data): void
    {
        $converter = WbProductsConverterProvider::getInstance();

        $this->expectException(ConvertException::class);
        $converter->convert($data);
    }

    /**
     * @return array
     */
    public function wrongDataProvider(): array
    {
        return [
            [null],
            [''],
            ['{}'],
            ['[]'],
            ['{"key": "value"}'],
            ['not a json string'],
            ['{"metadata": {"_name": "some name"}}'],
            ['{"metadata": {"name": "some name"}, "data": {"products": null}}']
        ];
    }

    /**
     * @return \string[][]
     */
    public function validDataProvider(): array
    {
        return [
            ['{"metadata": {"name": "some name"}, "data": {"products": []}}'],
            ['{"metadata": {"name": "some name"}, "data": {"products": [{"name": "Name", "brand": "Brand"}]}}']
        ];
    }
}
