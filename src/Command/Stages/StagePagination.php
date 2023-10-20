<?php

declare(strict_types=1);

namespace App\Command\Stages;

/**
 * Стадия разбиения парсинга по страницам.
 */
class StagePagination
{
    /**
     * @return \Generator
     */
    public function __invoke(): \Generator
    {
        foreach(range(1, 10) as $page) {
            yield $page;
        }
    }
}
