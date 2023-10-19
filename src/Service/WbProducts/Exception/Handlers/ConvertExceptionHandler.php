<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Exception\Handlers;

class ConvertExceptionHandler extends AbstractExceptionHandler
{
    private const DEFAULT_ERROR_MSG = 'Ошибка конвертации данных';

    /**
     * @param \Throwable $prevException
     * @return string
     */
    public function createUserFriendlyMessageFor(\Throwable $prevException): string
    {
        return sprintf('%s (%s)', self::DEFAULT_ERROR_MSG, $prevException->getMessage());
    }
}
