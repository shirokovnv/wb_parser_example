<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\WbProductsSearchForm;
use App\Service\WbProducts\Repository\WbProductsRepositoryInterface;

class WbSearchController extends AppController
{
    /**
     * @param WbProductsRepositoryInterface $repository
     * @return void
     */
    public function index(
        WbProductsRepositoryInterface $repository
    ) {
        $wbProductsSearch = new WbProductsSearchForm();
        $products = null;

        if ($this->request->is('post')) {
            if ($wbProductsSearch->execute($this->request->getData())) {
                $query = (string)$wbProductsSearch->getData('query');
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
        $this->set('wbProductsSearch', $wbProductsSearch);
        $this->set('products', $products);
    }
}
