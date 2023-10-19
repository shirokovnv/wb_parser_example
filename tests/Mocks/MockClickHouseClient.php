<?php

declare(strict_types=1);

namespace App\Test\Mocks;

use ClickHouseDB\Client;
use ClickHouseDB\Statement;
use Mockery as m;

class MockClickHouseClient
{
    /**
     * @return Client
     */
    public static function createInstanceForWriting(): Client
    {
        return m::mock(Client::class)
            ->shouldReceive('write')
            ->once()
            ->andReturn(m::mock(Statement::class))
            ->getMock();
    }
}
