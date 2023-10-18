<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Eggheads\CakephpClickHouse\ClickHouse;

/**
 * ClickHouseInit command.
 */
class ClickHouseInitCommand extends AbstractClickhouseCommand
{
    /**
     * Implement this method with your command's logic.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|void|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        // TODO: dependency injection needed ?
        $client = ClickHouse::getInstance()->getClient();
        $client->write('
            CREATE TABLE IF NOT EXISTS {table_name} (
                name String,
                position UInt32,
                brand String,
                query String
            )
            ENGINE = MergeTree()
            ORDER BY (query, position);
        ', ['table_name' => self::TABLE_NAME]);

        $io->out(sprintf("Table %s created\r\n", self::TABLE_NAME));

        return self::CODE_SUCCESS;
    }
}
