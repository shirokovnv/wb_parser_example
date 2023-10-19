<?php

declare(strict_types=1);

namespace App\Test\TestCase\Service\WbProducts\Exception;

use App\Service\WbProducts\Converter\Exception\ConvertException;
use App\Service\WbProducts\Exception\UserFriendlyException;
use App\Service\WbProducts\Exception\WbProductsExceptionHandler;
use Cake\Http\Client\Exception\ClientException;
use ClickHouseDB\Exception\QueryException;
use Eggheads\CakephpClickHouse\Exception\FieldNotFoundException;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @covers \App\Service\WbProducts\Exception\WbProductsExceptionHandler
 *
 * @group service
 * @group wbProducts
 */
class WbProductsExceptionHandlerTest extends TestCase
{
    /**
     * @dataProvider exceptionDataProvider
     * @param \Exception $exception
     * @return void
     */
    public function testCanMapDifferentExceptions(\Exception $exception)
    {
        $logger = $this->getMockLoggerInstance();
        $handler = $this->getHandlerInstance($logger);

        $userFriendlyException = $handler->handle($exception);

        $this->assertInstanceOf(UserFriendlyException::class, $userFriendlyException);
        $this->assertSame($exception, $userFriendlyException->getPrevious());
        $this->assertEquals($exception->getCode(), $userFriendlyException->getCode());
    }

    /**
     * @return array<\Exception>
     */
    public function exceptionDataProvider(): array
    {
        return [
            // Ошибки http-клиента
            [new ClientException('SOME EXCEPTION', 400)],
            [new ClientException('SOME EXCEPTION', 401)],
            [new ClientException('SOME EXCEPTION', 422)],
            [new ClientException('SOME EXCEPTION', 429)],

            // Ошибки конвертера
            [new ConvertException('SOME EXCEPTION')],

            // Ошибки ClickHouse
            [new QueryException('SOME EXCEPTION')],
            [new FieldNotFoundException('SOME FIELD NOT FOUND')],

            // Просто какая-то ошибка
            [new \Exception('SOME EXCEPTION')]
        ];
    }

    /**
     * @return LoggerInterface
     */
    private function getMockLoggerInstance(): LoggerInterface
    {
        return m::mock(LoggerInterface::class)
            ->shouldReceive('log')
            ->once()
            ->andReturns()
            ->getMock();
    }

    /**
     * @param LoggerInterface $loggerDependency
     * @return WbProductsExceptionHandler
     */
    private function getHandlerInstance(LoggerInterface $loggerDependency): WbProductsExceptionHandler
    {
        return new WbProductsExceptionHandler($loggerDependency);
    }
}
