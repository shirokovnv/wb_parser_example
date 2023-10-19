<?php

declare(strict_types=1);

namespace App\Test\TestCase\Service\WbProducts\Repository;

use App\Model\Table\WbProductsTableInterface;
use App\Service\WbProducts\DTO\WbProductEntity;
use App\Service\WbProducts\Repository\WbProductsRepository;
use App\Service\WbProducts\Repository\WbProductsRepositoryInterface;
use App\Test\TestCase\AbstractWithFakerTestCase;
use ClickHouseDB\Exception\QueryException;
use ClickHouseDB\Statement;
use Mockery as m;

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
        $mockTable = $this->getMockClickHouseTableForSelect(
            $this->getMockStatement()
        );

        $repository = $this->getRepositoryInstance($mockTable);
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
        $products = $this->createRandomProducts($userQuery, $count = 100);

        $mockTable = $this->getMockClickHouseTableForSelect(
            $this->getMockStatement($products)
        );

        $repository = $this->getRepositoryInstance($mockTable);
        $productEntities = $repository->getByQueryString($this->faker->sentence);

        $this->assertCount($count, $productEntities);
        foreach($productEntities as $productEntity) {
            $this->assertInstanceOf(WbProductEntity::class, $productEntity);
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
        $mockTable = $this->getMockClickHouseTableForInsert();

        // Проверяем только вызов метода
        $repository = $this->getRepositoryInstance($mockTable);
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

        $mockTable = $this->getMockClickHouseTableForInsert(true);

        // Проверяем только вызов метода
        $repository = $this->getRepositoryInstance($mockTable);
        $repository->bulkInsert([]);
    }

    /**
     * @param Statement $statement
     * @return WbProductsTableInterface
     */
    private function getMockClickHouseTableForSelect(Statement $statement): WbProductsTableInterface
    {
        return m::mock(WbProductsTableInterface::class)
            ->shouldReceive('select')
            ->once()
            ->andReturn($statement)
            ->getMock();
    }

    /**
     * @param bool $shouldThrowException
     *
     * @return WbProductsTableInterface
     */
    private function getMockClickHouseTableForInsert(
        bool $shouldThrowException = false
    ): WbProductsTableInterface {
        $mock = m::mock(WbProductsTableInterface::class)
            ->shouldReceive('bulkInsert')
            ->once();

        if ($shouldThrowException) {
            $mock->andThrow(new QueryException('SOME EXCEPTION MESSAGE'));
        } else {
            $mock->andReturns();
        }

        return $mock->getMock();
    }

    /**
     * @param array $returnedRows
     * @return Statement
     */
    private function getMockStatement(array $returnedRows = []): Statement
    {
        return m::mock(Statement::class)
            ->shouldReceive('rows')
            ->andReturn($returnedRows)
            ->getMock();
    }

    /**
     * @param WbProductsTableInterface $tableDependency
     * @return WbProductsRepositoryInterface
     */
    private function getRepositoryInstance(WbProductsTableInterface $tableDependency): WbProductsRepositoryInterface
    {
        return new WbProductsRepository($tableDependency);
    }

    /**
     * @param string $userQuery
     * @param int $count
     *
     * @return array{name: string, brand: string, position: int, query: string}
     */
    private function createRandomProducts(string $userQuery, int $count = 100): array
    {
        if ($count <= 0) {
            throw new \LogicException('Products count must be positive integer.');
        }

        $products = [];
        for ($i = 0; $i < $count; $i++) {
            $products[] = [
                'name' => $this->faker->name,
                'brand' => $this->faker->sentence,
                'position' => $this->faker->randomNumber(),
                'query' => $userQuery
            ];
        }

        return $products;
    }
}
