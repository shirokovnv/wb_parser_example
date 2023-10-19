<?php

declare(strict_types=1);

namespace App\Test\Traits;

use Faker\Factory;
use Faker\Generator;

/**
 * Подмешивает функциональность для генерации фейковых данных.
 */
trait WithFaker
{
    /**
     * @var Generator
     */
    protected Generator $faker;

    /**
     * @return void
     */
    public function setUpFaker(): void
    {
        $this->faker = Factory::create();
    }

    /**
     * @return void
     */
    public function tearDownFaker(): void
    {
        if (isset($this->faker)) {
            unset($this->faker);
        }
    }
}
