<?php
declare(strict_types=1);

namespace App\Test\TestCase\Form;

use App\Form\WbProductsSearchForm;
use Cake\TestSuite\TestCase;

/**
 * App\Form\WbProductsSearchForm Test Case
 */
class WbProductsSearchFormTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Form\WbProductsSearchForm
     */
    protected $WbProductsSearch;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->WbProductsSearch = new WbProductsSearchForm();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->WbProductsSearch);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Form\WbProductsSearchForm::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
