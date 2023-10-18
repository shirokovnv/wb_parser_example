<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\WbProductsSearchForm;
use App\Service\WbProducts\DTO\WbProductEntity;
use App\Service\WbProducts\Exception\WbProductsExceptionHandler;
use App\Service\WbProducts\Repository\WbProductsRepositoryInterface;

/**
 * Зона ответственности: Управление отображением страницы с формой поиска по товарам.
 */
class WbSearchController extends AppController
{
    /**
     * @param WbProductsRepositoryInterface $repository
     * @param WbProductsExceptionHandler $exceptionHandler
     *
     * @return void
     */
    public function index(
        WbProductsRepositoryInterface $repository,
        WbProductsExceptionHandler $exceptionHandler
    ) {
        $errorMessage = '';

        try {
            [$wbProductsSearch, $products] = $this->processFormLogic($repository);
        } catch (\Throwable $exception) {
            // Log original exception
            $this->log($exception->getMessage());

            $errorMessage = $exceptionHandler->handle($exception)->getMessage();
            $wbProductsSearch = new WbProductsSearchForm();
            $products = null;
        }

        if ($errorMessage !== '') {
            $this->Flash->error($errorMessage);
        }

        $this->set('wbProductsSearch', $wbProductsSearch);
        $this->set('products', $products);
    }

    /**
     * @param WbProductsRepositoryInterface $repository
     *
     * @return array{0: WbProductsSearchForm, 1: array<WbProductEntity>|null}
     */
    private function processFormLogic(WbProductsRepositoryInterface $repository): array
    {
        $wbProductsSearch = new WbProductsSearchForm();
        $products = null;

        if ($this->request->is('post')) {
            if ($wbProductsSearch->execute($this->request->getData())) {
                $query = (string) $wbProductsSearch->getData('query');
                $products = $repository->getByQueryString($query);

                if (count($products) > 0) {
                    $this->Flash->success('Найдены результаты поиска.');
                } else {
                    $this->Flash->info('Ничего не найдено.');
                }
            } else {
                $this->Flash->error('Возникла проблема с формой.');
            }
        }

        return [$wbProductsSearch, $products];
    }
}
