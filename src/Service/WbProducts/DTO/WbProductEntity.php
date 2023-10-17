<?php

declare(strict_types=1);

namespace App\Service\WbProducts\DTO;

class WbProductEntity
{
    /**
     * @param string $name
     * @param string $brand
     * @param int $position
     * @param string $query
     */
    public function __construct(
        private string $name,
        private string $brand,
        private int $position,
        private string $query
    )
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }
}
