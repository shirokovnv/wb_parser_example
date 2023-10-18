<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Exception;

use App\Service\WbProducts\Converter\Exception\ConvertException;
use ClickHouseDB\Exception\QueryException;
use Eggheads\CakephpClickHouse\Exception\FieldNotFoundException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Зона ответственности: логирование ошибок при работе с товарами и преобразование их в user-friendly ошибки.
 */
class WbProductsExceptionHandler
{
    private const CLIENT_ERROR_CODES = [
        401 => 'Требуется аутентификация.',
        403 => 'Ошибка авторизации.',
        422 => 'Ошибка в поисковой фразе.',
        429 => 'Слишком много запросов к сервису search.wb.ru. Попробуйте позже.',
        500 => 'Ошибка сервера.',
        502 => 'Сервис search.wb.ru не доступен.',
        503 => 'Сервис search.wb.ru не ответил вовремя.',
    ];

    private const DEFAULT_CLIENT_ERROR_MSG = 'Ошибка работы с сервисом search.wb.ru';

    private const DEFAULT_CONVERT_ERROR_MSG = 'Ошибка конвертации данных: ';

    private const DEFAULT_CLICKHOUSE_ERROR_MSG = 'Ошибка при работе с ClickHouse: ';

    private const DEFAULT_ERROR_MSG = 'Неизвестная ошибка';

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(private LoggerInterface $logger)
    {
    }

    /**
     * @param \Throwable $exception
     * @return UserFriendlyException
     */
    public function handle(\Throwable $exception): UserFriendlyException
    {
        // Log original exception
        $this->logger->log(LogLevel::ERROR, $exception->getMessage());

        // Map exception to User-Friendly exception
        $message = $this->mapExceptionMessage($exception);

        return new UserFriendlyException($message, $exception->getCode(), $exception);
    }

    /**
     * @param \Throwable $exception
     * @return string
     */
    private function mapExceptionMessage(\Throwable $exception): string
    {
        return match(true) {
            $exception instanceof ClientExceptionInterface =>
                self::CLIENT_ERROR_CODES[$exception->getCode()] ?? self::DEFAULT_CLIENT_ERROR_MSG,
            $exception instanceof ConvertException => self::DEFAULT_CONVERT_ERROR_MSG . $exception->getMessage(),
            $exception instanceof QueryException ||
            $exception instanceof FieldNotFoundException => self::DEFAULT_CLICKHOUSE_ERROR_MSG . $exception->getMessage(),
            default => self::DEFAULT_ERROR_MSG
        };
    }
}
