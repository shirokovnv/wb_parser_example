<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Converter;

use App\Service\WbProducts\Entity\Product;

interface WbProductsConverterInterface
{
    /**
     * @param mixed $rawData
     * @param int $startIndex
     *
     * @return array<Product>
     *
     * Конвертер на входе принимает любые "сырые данные" и перерабатывает их в массив структур Product.
     */
    public function convert(mixed $rawData, int $startIndex = 1): array;
}
