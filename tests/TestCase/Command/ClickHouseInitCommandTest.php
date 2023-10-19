<?php

declare(strict_types=1);

namespace App\Test\TestCase\Command;

use Cake\Console\CommandInterface;

/**
 * App\Command\ClickHouseInitCommand Test Case
 *
 * @uses \App\Command\ClickHouseInitCommand
 *
 * @group console
 * @group wbProducts
 */
class ClickHouseInitCommandTest extends AbstractClickHouseCommandTestCase
{
    /**
     * Test execute method
     *
     * @return void
     * @uses \App\Command\ClickHouseInitCommand::execute()
     */
    public function testExecute(): void
    {
        $this->exec('click_house_init');
        $this->assertOutputContains('Table wbProducts created');
        $this->assertExitCode(CommandInterface::CODE_SUCCESS);
    }
}
