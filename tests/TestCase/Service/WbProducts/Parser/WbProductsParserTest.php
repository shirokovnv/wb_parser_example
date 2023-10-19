<?php

declare(strict_types=1);

namespace App\Test\TestCase\Service\WbProducts\Parser;

use App\Service\WbProducts\Parser\Client\WbSearchRequest;
use App\Service\WbProducts\Parser\Client\WbSearchResponse;
use App\Service\WbProducts\Parser\WbProductsParser;
use App\Service\WbProducts\Parser\WbProductsParserInterface;
use App\Test\TestCase\AbstractWithFakerTestCase;
use App\Test\TestCase\Service\WbProducts\Parser\Factory\WbSearchExceptionFactory;
use App\Test\TestCase\Service\WbProducts\Parser\Factory\WbSearchResponseFactory;
use Mockery as m;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @covers \App\Service\WbProducts\Parser\WbProductsParser
 *
 * @group service
 * @group wbProducts
 */
class WbProductsParserTest extends AbstractWithFakerTestCase
{
    /**
     *
     * Тестируем случай, когда парсер не может достучаться до search.wb.ru.
     *
     * @return void
     */
    public function testParserThrowsHttpException(): void
    {
        $exception = WbSearchExceptionFactory::createRandomClientException();
        $mockClient = $this->getMockHttpClient($exception);
        $parser = $this->getParserInstance($mockClient);

        $this->expectException(ClientExceptionInterface::class);

        $parser->parseByUserParams($this->faker->sentence, $this->faker->randomDigit() + 1);
    }

    /**
     * Тестируем случай, когда парсер успешно возвращает данные.
     *
     * @return void
     */
    public function testParserReturnsSuccessfulResponse(): void
    {
        $userQuery = $this->faker->sentence;
        $numProducts = $this->faker->numberBetween(10, 100);

        $response = WbSearchResponseFactory::createSuccessfulResponse($userQuery, $numProducts);

        $mockClient = $this->getMockHttpClient($response, true);
        $parser = $this->getParserInstance($mockClient);

        $parserResponse = $parser->parseByUserParams($userQuery, 1);

        $this->assertInstanceOf(WbSearchResponse::class, $parserResponse);

        $productsJson = json_decode($parserResponse->getContent(), true, JSON_THROW_ON_ERROR);

        $response->getBody()->rewind();

        // Тестируем наличие данных в ответе
        $this->assertEquals($response->getBody()->getContents(), $parserResponse->getContent());

        // Тестируем правильную JSON-структуру
        $this->assertArrayHasKey('metadata', $productsJson);
        $this->assertIsArray($productsJson['metadata']);
        $this->assertArrayHasKey('data', $productsJson);
        $this->assertIsArray($productsJson['data']);

        $this->assertArrayHasKey('name', $productsJson['metadata']);
        $this->assertEquals($userQuery, $productsJson['metadata']['name']);
        $this->assertArrayHasKey('products', $productsJson['data']);
        $this->assertIsArray($productsJson['data']['products']);
        $this->assertCount($numProducts, $productsJson['data']['products']);
    }

    /**
     * Тестируем случай, когда парсер возвращает пустой ответ.
     *
     * @return void
     */
    public function testParserReturnsEmptyResponse(): void
    {
        $response = WbSearchResponseFactory::createEmptyResponse();

        $mockClient = $this->getMockHttpClient($response, true);
        $parser = $this->getParserInstance($mockClient);

        $parserResponse = $parser->parseByUserParams($this->faker->sentence, $this->faker->randomDigit() + 1);

        $this->assertInstanceOf(WbSearchResponse::class, $parserResponse);

        $response->getBody()->rewind();

        $this->assertEquals($response->getBody()->getContents(), $parserResponse->getContent());

        $productsJson = json_decode($parserResponse->getContent(), true, JSON_THROW_ON_ERROR);
        $this->assertEmpty($productsJson);
    }

    /**
     * @param ResponseInterface|ClientExceptionInterface $responseOrException
     * @param bool $shouldCheckRequestArg
     *
     * @return ClientInterface
     */
    private function getMockHttpClient(
        ResponseInterface|ClientExceptionInterface $responseOrException,
        bool $shouldCheckRequestArg = false
    ): ClientInterface {
        $mock = m::mock(ClientInterface::class)
            ->expects('sendRequest')
            ->once();

        if ($shouldCheckRequestArg) {
            $mock->with(m::on(function ($request) {
                return $request instanceof WbSearchRequest
                    && $request->getUri()->getHost() === 'search.wb.ru'
                    && $request->getUri()->getPath() === '/exactmatch/ru/common/v4/search';
            }));
        }

        if ($responseOrException instanceof ClientExceptionInterface) {
            return $mock
                ->andThrow($responseOrException)
                ->getMock();
        }

        return $mock
            ->andReturn($responseOrException)
            ->getMock();
    }

    /**
     * @param ClientInterface $clientDependency
     * @return WbProductsParserInterface
     */
    private function getParserInstance(ClientInterface $clientDependency): WbProductsParserInterface
    {
        return new WbProductsParser($clientDependency);
    }
}
