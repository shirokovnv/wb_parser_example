<?php

declare(strict_types=1);

namespace App\Test\Mocks;

use Cake\Console\ConsoleIo;
use Mockery as m;

class ConsoleIoProvider
{
    /**
     * @param string $calledMethod
     * @return ConsoleIo
     */
    public static function getInstance(string $calledMethod = 'info'): ConsoleIo
    {
        return m::mock(ConsoleIo::class)
            ->shouldReceive($calledMethod)
            ->once()
            ->getMock();
    }
}
