<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Exception\Handlers;

class ClientExceptionHandler extends AbstractExceptionHandler
{
    private const ERROR_CODES = [
        401 => 'Требуется аутентификация.',
        403 => 'Ошибка авторизации.',
        422 => 'Ошибка в поисковой фразе.',
        429 => 'Слишком много запросов к сервису search.wb.ru. Попробуйте позже.',
        500 => 'Ошибка сервера.',
        502 => 'Сервис search.wb.ru не доступен.',
        503 => 'Сервис search.wb.ru не ответил вовремя.',
    ];

    private const DEFAULT_ERROR_MSG = 'Ошибка работы с сервисом search.wb.ru';

    /**
     * @param \Throwable $prevException
     * @return string
     */
    public function createUserFriendlyMessageFor(\Throwable $prevException): string
    {
        return self::ERROR_CODES[$prevException->getCode()] ?? self::DEFAULT_ERROR_MSG;
    }
}
