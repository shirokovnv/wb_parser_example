<?php

declare(strict_types=1);

namespace App\Test\TestCase;

use App\Test\Traits\WithFaker;
use PHPUnit\Framework\TestCase;

abstract class AbstractWithFakerTestCase extends TestCase
{
    use WithFaker;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->tearDownFaker();
    }
}
