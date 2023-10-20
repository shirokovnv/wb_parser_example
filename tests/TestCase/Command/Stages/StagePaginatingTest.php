<?php

declare(strict_types=1);

namespace App\Test\TestCase\Command\Stages;

use App\Command\Stages\StagePagination;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Command\Stages\StagePagination
 *
 * @group console
 * @group wbProducts
 */
class StagePaginatingTest extends TestCase
{
    /**
     * @return void
     */
    public function testYieldPageNumbersFromOneToTen(): void
    {
        $stage = new StagePagination();

        $currentPage = 0;
        foreach($stage() as $page) {
            $currentPage++;
            $this->assertIsNumeric($page);
            $this->assertEquals($currentPage, $page);
        }
        $this->assertEquals(10, $currentPage);
    }
}
