<?php

declare(strict_types=1);

namespace App\Command\Stages;

interface StageInterface
{
    /**
     * @param array|\Generator $flow
     * @return \Generator
     */
    public function __invoke(array|\Generator $flow = []): \Generator;
}
