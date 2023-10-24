<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

/**
 * Зона ответственности: Создание таблицы wbProducts.
 */
class ClickHouseInitCommand extends AbstractClickhouseCommand
{
    /**
     * @param Arguments $args
     * @param ConsoleIo $io
     * @return int|void|null
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->clickHouseClient->write('
            CREATE TABLE IF NOT EXISTS {table_name} (
                name String,
                position UInt32,
                brand String,
                query String
            )
            ENGINE = ReplacingMergeTree()
            PARTITION BY sipHash64(query)
            ORDER BY (query, position);
        ', ['table_name' => self::TABLE_NAME]);

        $io->out(sprintf("Table %s created\r\n", self::TABLE_NAME));

        return self::CODE_SUCCESS;
    }
}
