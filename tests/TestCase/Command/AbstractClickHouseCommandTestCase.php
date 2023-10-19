<?php

declare(strict_types=1);

namespace App\Test\TestCase\Command;

use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

abstract class AbstractClickHouseCommandTestCase extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        // TODO: нужно для тестов команд с зависимостями в конструкторе
        // @see App\Test\TestCase\Command\Application
        $this->setAppNamespace('App\Test\TestCase\Command\Stubs');
        $this->useCommandRunner();
    }
}
