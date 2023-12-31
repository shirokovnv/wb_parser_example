<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

/**
 * Зона ответственности: Физическое удаление таблицы wbProducts.
 */
class ClickHouseDropCommand extends AbstractClickhouseCommand
{
    /**
     * @param Arguments $args
     * @param ConsoleIo $io
     * @return int|void|null
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->clickHouseClient->write("DROP TABLE IF EXISTS {table_name}", ['table_name' => self::TABLE_NAME]);

        $io->out(sprintf("Table %s dropped\r\n", self::TABLE_NAME));

        return self::CODE_SUCCESS;
    }
}
