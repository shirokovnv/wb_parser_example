<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Exception\Handlers;

class ClickHouseExceptionHandler extends AbstractExceptionHandler
{
    private const DEFAULT_ERROR_MSG = 'Ошибка при работе с ClickHouse';

    /**
     * @param \Throwable $prevException
     * @return string
     */
    public function createUserFriendlyMessageFor(\Throwable $prevException): string
    {
        return sprintf('%s (%s)', self::DEFAULT_ERROR_MSG, $prevException->getMessage());
    }
}
