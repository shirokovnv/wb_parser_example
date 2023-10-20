<?php

declare(strict_types=1);

namespace App\Test\TestCase\Service\WbProducts\Repository;

use App\Service\WbProducts\DTO\Product;
use App\Test\Mocks\ClickHouse\ClickHouseTableProvider;
use App\Test\Mocks\WbProductsEndpoint\Providers\ProductProvider;
use App\Test\Mocks\WbProductsEndpoint\Providers\WbProductsRepositoryProvider;
use App\Test\TestCase\AbstractWithFakerTestCase;
use ClickHouseDB\Exception\QueryException;

/**
 * @covers \App\Service\WbProducts\Repository\WbProductsRepository
 *
 * @group service
 * @group wbProducts
 */
class WbProductsRepositoryTest extends AbstractWithFakerTestCase
{
    /**
     * Тестируем случай, когда получен пустой список.
     *
     * @return void
     */
    public function testSelectEmptyData(): void
    {
        $statement = ClickHouseTableProvider::getStatement();
        $mockTable = ClickHouseTableProvider::getInstanceForSelect($statement);

        $repository = WbProductsRepositoryProvider::getInstance($mockTable);
        $repository->getByQueryString($this->faker->sentence);

        $this->assertTrue(true);
    }

    /**
     * Тестируем случай, когда получен не пустой список.
     *
     * @return void
     */
    public function testSelectNonEmptyData(): void
    {
        $userQuery = $this->faker->sentence;
        $products = ProductProvider::createRandomProducts($userQuery, $count = 100);

        $statement = ClickHouseTableProvider::getStatement($products);
        $mockTable = ClickHouseTableProvider::getInstanceForSelect($statement);

        $repository = WbProductsRepositoryProvider::getInstance($mockTable);
        $productEntities = $repository->getByQueryString($this->faker->sentence);

        $this->assertCount($count, $productEntities);
        foreach($productEntities as $productEntity) {
            $this->assertInstanceOf(Product::class, $productEntity);
            $this->assertEquals($userQuery, $productEntity->getQuery());
        }
    }

    /**
     * Тестируем случай вставки записей - ВСЕ ОК.
     *
     * @return void
     */
    public function testDataInsertionOk(): void
    {
        $mockTable = ClickHouseTableProvider::getInstanceForInsert();

        // Проверяем только вызов метода
        $repository = WbProductsRepositoryProvider::getInstance($mockTable);
        $result = $repository->bulkInsert([]);
        $this->assertNull($result);
    }

    /**
     * Тестируем случай, когда при вставке записей произошла ошибка.
     *
     * @return void
     */
    public function testDataInsertionError(): void
    {
        $this->expectException(QueryException::class);

        $mockTable = ClickHouseTableProvider::getInstanceForInsert(true);

        // Проверяем только вызов метода
        $repository = WbProductsRepositoryProvider::getInstance($mockTable);
        $repository->bulkInsert([]);
    }
}
