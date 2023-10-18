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
     * TODO: why we use mixed as a type here ? It's some kind of extension point, for different implementations.
     */
    public function convert(mixed $rawData, int $startIndex = 1): array;
}
