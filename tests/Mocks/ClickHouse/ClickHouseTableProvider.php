<?php

declare(strict_types=1);

namespace App\Test\Mocks\ClickHouse;

use App\Model\Table\WbProductsTableInterface;
use ClickHouseDB\Exception\QueryException;
use ClickHouseDB\Statement;
use Mockery as m;

class ClickHouseTableProvider
{
    /**
     * @param bool $shouldThrowException
     *
     * @return WbProductsTableInterface
     */
    public static function getInstanceForInsert(
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
     * @param Statement $statement
     * @return WbProductsTableInterface
     */
    public static function getInstanceForSelect(Statement $statement): WbProductsTableInterface
    {
        return m::mock(WbProductsTableInterface::class)
            ->shouldReceive('select')
            ->once()
            ->andReturn($statement)
            ->getMock();
    }

    /**
     * @param array $returnedRows
     * @return Statement
     */
    public static function getStatement(array $returnedRows = []): Statement
    {
        return m::mock(Statement::class)
            ->shouldReceive('rows')
            ->andReturn($returnedRows)
            ->getMock();
    }
}
