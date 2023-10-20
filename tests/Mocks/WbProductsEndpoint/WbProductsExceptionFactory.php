<?php

declare(strict_types=1);

namespace App\Test\Mocks\WbProductsEndpoint;

use Cake\Http\Client\Exception\ClientException;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * Фабрика по генерированию случайных исключений для Http-Client.
 */
class WbProductsExceptionFactory
{
    private const CODES = [
        400 => 'Bad request',
        401 => 'Unauthenticated',
        403 => 'Authorization required',
        422 => 'Unprocessable entity',
        429 => 'Too many requests'
    ];

    /**
     * @return ClientExceptionInterface
     */
    public static function createRandomClientException(): ClientExceptionInterface
    {
        $randomCode = array_rand(self::CODES);
        $randomMessage = self::CODES[$randomCode];

        return new ClientException($randomMessage, $randomCode);
    }
}
