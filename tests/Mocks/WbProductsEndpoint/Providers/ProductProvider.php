<?php

declare(strict_types=1);

namespace App\Test\Mocks\WbProductsEndpoint\Providers;

use Faker\Factory;

class ProductProvider
{
    /**
     * @param string $userQuery
     * @param int $count
     *
     * @return array{name: string, brand: string, position: int, query: string}
     */
    public static function createRandomProducts(string $userQuery, int $count = 100): array
    {
        $faker = Factory::create();

        if ($count <= 0) {
            throw new \LogicException('Products count must be positive integer.');
        }

        $products = [];
        for ($i = 0; $i < $count; $i++) {
            $products[] = [
                'name' => $faker->name,
                'brand' => $faker->sentence,
                'position' => $faker->randomNumber(),
                'query' => $userQuery
            ];
        }

        return $products;
    }
}
