<?php

declare(strict_types=1);

namespace App\Test\Mocks;

use Mockery as m;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpClientProvider
{
    /**
     * @param ResponseInterface|ClientExceptionInterface $responseOrException
     * @param string|null $checkRequestType
     * @param string|null $checkRequestPath
     * @param string|null $checkHost
     *
     * @return ClientInterface
     */
    public static function getInstance(
        ResponseInterface|ClientExceptionInterface $responseOrException,
        ?string $checkRequestType = null,
        ?string $checkHost = null,
        ?string $checkRequestPath = null
    ): ClientInterface {
        $mock = m::mock(ClientInterface::class)
            ->expects('sendRequest')
            ->once();

        $mock->with(m::on(function (RequestInterface $request) use ($checkRequestType, $checkRequestPath, $checkHost) {

            $isValid = ($checkRequestType === null || $request instanceof $checkRequestType);
            $isValid = $isValid && ($checkHost === null || $request->getUri()->getHost() === $checkHost);
            $isValid = $isValid && ($checkRequestPath === null || $request->getUri()->getPath() === $checkRequestPath);

            return $isValid;
        }));

        return $responseOrException instanceof ClientExceptionInterface
            ? $mock->andThrow($responseOrException)->getMock()
            : $mock->andReturn($responseOrException)->getMock();
    }
}
