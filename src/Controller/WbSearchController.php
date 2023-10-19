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
        $currentPage = (int) ($this->request->getQuery('page') ?? 1);
        // Страница может находиться в диапазоне 1..10
        $currentPage = max(1, min(10, $currentPage));
        $limit = 100;
        $offset = ($currentPage - 1) * $limit;

        try {
            [$productSearchForm, $userQuery, $errorMessage] = $this->processFormLogic();
            $products = $this->processProductSearchQuery($repository, $userQuery, $limit, $offset);

        } catch (\Throwable $exception) {
            // Log original exception
            $this->log($exception->getMessage());

            $errorMessage = $exceptionHandler->handle($exception)->getMessage();
            $productSearchForm = new WbProductsSearchForm();
            $products = null;
            $userQuery = null;
        }

        if ($errorMessage !== null) {
            $this->Flash->error($errorMessage);
        }

        $this->set('productSearchForm', $productSearchForm);
        $this->set('products', $products);
        $this->set('userQuery', $userQuery);
        $this->set('currentPage', $currentPage);
    }

    /**
     * @param WbProductsRepositoryInterface $repository
     * @param string|null $query
     * @param int $limit
     * @param int $offset
     *
     * @return WbProductEntity[]|null
     */
    private function processProductSearchQuery(
        WbProductsRepositoryInterface $repository,
        ?string $query,
        int $limit,
        int $offset
    ): ?array {
        if ($query !== null) {
            $products = $repository->getByQueryString($query, $limit, $offset);

            if (count($products) > 0) {
                $this->Flash->success('Найдены результаты поиска.');
            } else {
                $this->Flash->info('Ничего не найдено.');
            }

            return $products;
        }

        return null;
    }

    /**
     * @return array{0: WbProductsSearchForm, 1: string|null, 2: string|null}
     */
    private function processFormLogic(): array
    {
        $productSearchForm = new WbProductsSearchForm();
        $query = $this->request->getQuery('query');
        $errorMessage = null;

        if ($this->request->is('post')) {
            if ($productSearchForm->execute($this->request->getData())) {
                $query = (string) $productSearchForm->getData('query');
            } else {
                $errorMessage = 'Возникла проблема с формой.';
            }
        }

        return [$productSearchForm, $query, $errorMessage];
    }
}
