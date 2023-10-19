<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Exception;

use App\Service\WbProducts\Converter\Exception\ConvertException;
use App\Service\WbProducts\Exception\Handlers\AbstractExceptionHandler;
use App\Service\WbProducts\Exception\Handlers\ClickHouseExceptionHandler;
use App\Service\WbProducts\Exception\Handlers\ClientExceptionHandler;
use App\Service\WbProducts\Exception\Handlers\ConvertExceptionHandler;
use App\Service\WbProducts\Exception\Handlers\DefaultExceptionHandler;
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
        $handler = $this->createHandlerFor($exception);

        return $handler->handle($exception);
    }

    /**
     * @param \Throwable $exception
     * @return AbstractExceptionHandler
     */
    private function createHandlerFor(\Throwable $exception): AbstractExceptionHandler
    {
        return match(true) {
            $exception instanceof ClientExceptionInterface => new ClientExceptionHandler(),
            $exception instanceof ConvertException => new ConvertExceptionHandler(),
            $exception instanceof QueryException ||
            $exception instanceof FieldNotFoundException => new ClickHouseExceptionHandler(),
            default => new DefaultExceptionHandler()
        };
    }
}
