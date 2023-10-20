<?php

declare(strict_types=1);

namespace App\Test\TestCase\Command\Stages;

use App\Command\Stages\StageParsing;
use App\Test\Mocks\HttpClientProvider;
use App\Test\Mocks\WbProductsEndpoint\Providers\WbProductsParserProvider;
use App\Test\Mocks\WbProductsEndpoint\WbProductsExceptionFactory;
use App\Test\Mocks\WbProductsEndpoint\WbProductsResponseFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * @covers \App\Command\Stages\StageParsing
 *
 * @group console
 * @group wbProducts
 */
class StageParsingTest extends TestCase
{
    /**
     * @return void
     */
    public function testReceivedDataFromEndpoint(): void
    {
        $userQuery = 'some query';
        $response = WbProductsResponseFactory::createSuccessfulResponse($userQuery);
        $httpClient = HttpClientProvider::getInstance($response);
        $parser = WbProductsParserProvider::getInstance($httpClient);

        $stage = new StageParsing($parser, $userQuery);

        foreach($stage([1, 2, 3]) as $parsedBody) {
            $response->getBody()->rewind();
            $this->assertStringContainsString($userQuery, $parsedBody);
        }
    }

    /**
     * @return void
     */
    public function testThrowsException(): void
    {
        $exception = WbProductsExceptionFactory::createRandomClientException();
        $httpClient = HttpClientProvider::getInstance($exception);
        $parser = WbProductsParserProvider::getInstance($httpClient);

        $stage = new StageParsing($parser, 'some query');

        $this->expectException(ClientExceptionInterface::class);
        foreach($stage([1]) as $exception) {
            // do nothing, just expect exception
        }
    }
}
