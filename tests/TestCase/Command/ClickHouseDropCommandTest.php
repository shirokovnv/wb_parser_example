<?php

declare(strict_types=1);

namespace App\Test\TestCase\Command;

use Cake\Console\CommandInterface;

/**
 * App\Command\ClickHouseDropCommand Test Case
 *
 * @uses \App\Command\ClickHouseDropCommand
 *
 * @group console
 * @group wbProducts
 */
class ClickHouseDropCommandTest extends AbstractClickHouseCommandTestCase
{
    /**
     * Test execute method
     *
     * @return void
     * @uses \App\Command\ClickHouseDropCommand::execute()
     */
    public function testExecute(): void
    {
        $this->exec('click_house_drop');
        $this->assertOutputContains('Table wbProducts dropped');
        $this->assertExitCode(CommandInterface::CODE_SUCCESS);
    }
}
