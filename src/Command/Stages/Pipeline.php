<?php

declare(strict_types=1);

namespace App\Command\Stages;

class Pipeline
{
    /**
     * @param array|\Generator $flow
     */
    public function __construct(private array|\Generator $flow)
    {
    }

    /**
     * @param callable $stage
     * @return Pipeline
     */
    public function pipe(callable $stage): Pipeline
    {
        $next = function () use ($stage) {
            foreach($stage($this->flow) as $item) {
                yield $item;
            }
        };

        return new self($next());
    }

    /**
     * @return \Generator
     */
    public function tap(): \Generator
    {
        foreach($this->flow as $item) {
            yield $item;
        }
    }

    /**
     * @param \Generator $pipeline
     * @param callable|null $callback
     * @return void
     */
    public static function iterate(\Generator $pipeline, callable|null $callback = null)
    {
        foreach($pipeline as $result) {
            if ($callback !== null) {
                $callback($result);
            }
        }
    }
}
