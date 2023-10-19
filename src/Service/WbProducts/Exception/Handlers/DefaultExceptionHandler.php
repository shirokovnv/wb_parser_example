<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Exception\Handlers;

class DefaultExceptionHandler extends AbstractExceptionHandler
{
    private const DEFAULT_ERROR_MSG = 'Неизвестная ошибка';

    /**
     * @param \Throwable $prevException
     * @return string
     */
    public function createUserFriendlyMessageFor(\Throwable $prevException): string
    {
        return self::DEFAULT_ERROR_MSG;
    }
}
