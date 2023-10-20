<?php

declare(strict_types=1);

namespace App\Command\Stages;

/**
 * Стадия разбиения парсинга по страницам.
 */
class StagePagination implements StageInterface
{
    /**
     * @param array|\Generator $flow
     * @return \Generator
     */
    public function __invoke(array|\Generator $flow = []): \Generator
    {
        foreach(range(1, 10) as $page) {
            yield $page;
        }
    }
}
