<?php

declare(strict_types=1);

namespace App\Test\TestCase\Command\Stages;

use App\Command\Stages\StageInsertion;
use App\Test\Mocks\ClickHouse\ClickHouseTableProvider;
use App\Test\Mocks\ConsoleIoProvider;
use App\Test\Mocks\WbProductsEndpoint\Providers\ProductProvider;
use App\Test\Mocks\WbProductsEndpoint\Providers\WbProductsRepositoryProvider;
use ClickHouseDB\Exception\QueryException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Command\Stages\StageInsertion
 *
 * @group console
 * @group wbProducts
 */
class StageInsertionTest extends TestCase
{
    /**
     * @return void
     */
    public function testInsertionSuccessful(): void
    {
        $table = ClickHouseTableProvider::getInstanceForInsert();
        $repository = WbProductsRepositoryProvider::getInstance($table);
        $io = ConsoleIoProvider::getInstance('out');
        $stage = new StageInsertion($repository, $io);

        $randomProducts = ProductProvider::createRandomProducts('some query');

        foreach($stage([$randomProducts]) as $products) {
            $this->assertEquals($randomProducts, $products);
        }
    }

    /**
     * @return void
     */
    public function testInsertionError(): void
    {
        $table = ClickHouseTableProvider::getInstanceForInsert(true);
        $repository = WbProductsRepositoryProvider::getInstance($table);
        $io = ConsoleIoProvider::getInstance('out');

        $stage = new StageInsertion($repository, $io);

        $products = ProductProvider::createRandomProducts('some query');

        $this->expectException(QueryException::class);
        foreach($stage($products) as $result) {
            // do nothing, just expect exception
        }
    }
}
