<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Converter;

use App\Service\WbProducts\DTO\WbProductEntity;

interface WbProductsConverterInterface
{
    /**
     * @param mixed $rawData
     * @param int $startIndex
     *
     * @return array<WbProductEntity>
     *
     * Конвертер на входе принимает любые "сырые данные" и перерабатывает их в массив структур WbProductEntity.
     */
    public function convert(mixed $rawData, int $startIndex = 1): array;
}
