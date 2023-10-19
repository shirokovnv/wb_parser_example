<?php

declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Form\WbProductsSearchForm;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * @covers \App\Controller\WbSearchController::index
 *
 * @group controller
 * @group wbProducts
 */
class WbSearchControllerTest extends TestCase
{
    use IntegrationTestTrait;

    private const ENDPOINT = '/wbSearch';

    /**
     * @return void
     */
    public function testIndex(): void
    {
        $this->get(self::ENDPOINT);
        $this->assertResponseOk();
        $this->assertResponseContains('Фраза');
        $this->assertResponseContains('Поиск');
        $this->assertResponseContains('<input type="text" name="query"');
    }

    /**
     * @return void
     */
    public function testIndexPostData(): void
    {
        $data = [
            'query' => 'some query'
        ];
        $this->enableCsrfToken();
        $this->post(self::ENDPOINT, $data);
        $this->assertResponseSuccess();
    }

    /**
     * @return void
     */
    public function testIndexCsrfProtection(): void
    {
        $data = [
            'query' => 'some query'
        ];
        $this->post(self::ENDPOINT, $data);
        $this->assertResponseCode(403);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testIndexValidationError(): void
    {
        $this->enableCsrfToken();
        $this->post(self::ENDPOINT, ['query' => '']);
        $this->assertResponseContains(WbProductsSearchForm::MSG_NOT_EMPTY);

        $this->post(self::ENDPOINT, ['query' => '%%']);
        $this->assertResponseContains(WbProductsSearchForm::MSG_REGEX_ERROR);

        $this->post(self::ENDPOINT, ['query' => random_bytes(1000)]);
        $this->assertResponseContains(WbProductsSearchForm::MSG_MAX_LENGTH);
    }
}
