<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Eggheads\CakephpClickHouse\ClickHouse;

/**
 * ClickHouseInit command.
 */
class ClickHouseInitCommand extends AbstractClickhouseCommand
{
    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        return $parser;
    }

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
                product_name String,
                position UInt32,
                brand_name String,
                query String,
                timestamp DateTime DEFAULT now()
            )
            ENGINE = MergeTree()
            PRIMARY KEY (position, timestamp);
        ', ['table_name' => self::TABLE_NAME]);

        echo sprintf("Table %s created\r\n", self::TABLE_NAME);
    }
}
