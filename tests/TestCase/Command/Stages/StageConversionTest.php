<?php

declare(strict_types=1);

namespace App\Test\TestCase\Command\Stages;

use App\Command\Stages\StageConversion;
use App\Service\WbProducts\Converter\Exception\ConvertException;
use App\Service\WbProducts\DTO\Product;
use App\Test\Mocks\ConsoleIoProvider;
use App\Test\Mocks\WbProductsEndpoint\Providers\WbProductsConverterProvider;
use App\Test\Mocks\WbProductsEndpoint\WbProductsResponseFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Command\Stages\StageConversion
 *
 * @group console
 * @group wbProducts
 */
class StageConversionTest extends TestCase
{
    /**
     * @return void
     */
    public function testConversionSuccessful(): void
    {
        $response = WbProductsResponseFactory::createSuccessfulResponse('some query');
        $converter = WbProductsConverterProvider::getInstance();
        $io = ConsoleIoProvider::getInstance();

        $stage = new StageConversion($converter, $io);

        foreach($stage([$response->getBody()->getContents()]) as $products) {
            $this->assertIsArray($products);
            foreach ($products as $product) {
                $this->assertInstanceOf(Product::class, $product);
            }
        }
    }

    /**
     * @return void
     */
    public function testConversionError(): void
    {
        $converter = WbProductsConverterProvider::getInstance();
        $io = ConsoleIoProvider::getInstance();
        $stage = new StageConversion($converter, $io);

        $this->expectException(ConvertException::class);

        foreach($stage(['']) as $products) {
            // do nothing, just expect exception
        }
    }
}
