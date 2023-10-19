<?php

declare(strict_types=1);

namespace App\Test\TestCase\Service\WbProducts\Parser\Client;

use App\Service\WbProducts\Parser\Client\WbSearchRequest;
use App\Test\TestCase\AbstractWithFakerTestCase;

/**
 * @covers \App\Service\WbProducts\Parser\Client\WbSearchRequest
 *
 * @group service
 * @group wbProducts
 */
class WbSearchRequestTest extends AbstractWithFakerTestCase
{
    /**
     * @return void
     */
    public function testValidUriAndQueryString(): void
    {
        $userQuery = $this->faker->word;
        $request = WbSearchRequest::fromUserParams($userQuery);
        $uri = (string) $request->getUri();

        $this->assertStringContainsString('search.wb.ru', $uri);
        $this->assertStringContainsString($userQuery, $uri);
    }
}
