<?php

declare(strict_types=1);

namespace App\Test\TestCase\Service\WbProducts\Parser\Client;

use App\Service\WbProducts\Parser\Client\WbSearchResponse;
use App\Test\TestCase\AbstractWithFakerTestCase;

/**
 * @covers \App\Service\WbProducts\Parser\Client\WbSearchResponse
 *
 * @group service
 * @group wbProducts
 */
class WbSearchResponseTest extends AbstractWithFakerTestCase
{
    /**
     * @return void
     */
    public function testContentMatch(): void
    {
        $content = 'some content';
        $response = new WbSearchResponse($content);
        $this->assertEquals($content, $response->getContent());
    }
}
