<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Exception\Handlers;

use App\Service\WbProducts\Exception\UserFriendlyException;

abstract class AbstractExceptionHandler
{
    /**
     * @param \Throwable $exception
     * @return UserFriendlyException
     */
    public function handle(\Throwable $exception): UserFriendlyException
    {
        $message = $this->createUserFriendlyMessageFor($exception);

        return new UserFriendlyException($message, $exception->getCode(), $exception);
    }

    /**
     * @param \Throwable $prevException
     * @return string
     */
    abstract public function createUserFriendlyMessageFor(\Throwable $prevException): string;
}
