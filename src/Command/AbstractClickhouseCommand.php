<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;

abstract class AbstractClickhouseCommand extends Command
{
    protected const TABLE_NAME = 'wbProducts';
}
