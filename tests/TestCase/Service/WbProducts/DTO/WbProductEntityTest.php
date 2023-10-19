<?php

declare(strict_types=1);

namespace App\Test\TestCase\Service\WbProducts\DTO;

use App\Service\WbProducts\DTO\WbProductEntity;
use App\Test\TestCase\AbstractWithFakerTestCase;

/**
 * @covers \App\Service\WbProducts\DTO\WbProductEntity
 *
 * @group service
 * @group wbProducts
 */
class WbProductEntityTest extends AbstractWithFakerTestCase
{
    /**
     * @return void
     */
    public function testGetters(): void
    {
        $product = new WbProductEntity(
            $name = $this->faker->name,
            $brand = $this->faker->name,
            $position = $this->faker->randomNumber(),
            $query = $this->faker->sentence
        );

        $this->assertEquals($name, $product->getName());
        $this->assertEquals($brand, $product->getBrand());
        $this->assertEquals($position, $product->getPosition());
        $this->assertEquals($query, $product->getQuery());
    }
}
