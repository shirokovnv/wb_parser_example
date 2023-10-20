<?php

declare(strict_types=1);

namespace App\Model\Table;

use App\Service\WbProducts\DTO\Product;
use Eggheads\CakephpClickHouse\AbstractClickHouseTable;
use Eggheads\CakephpClickHouse\Exception\FieldNotFoundException;

class WbProductsClickhouseTable extends AbstractClickHouseTable implements WbProductsTableInterface
{
    public const TABLE = 'wbProducts'; // указывать, если имя таблицы отличается от *ClickHouseTable
    public const WRITER_CONFIG = 'default'; // указывать в случае необходимости записи в таблицу

    /** @var int Размер порции данных при отправке в транзакции */
    private const PAGE_SIZE = 1000;

    /**
     * @param array<Product> $products
     * @return void
     *
     * @throws FieldNotFoundException
     * @throws \Exception
     */
    public function bulkInsert(array $products): void
    {
        $transaction = $this->createTransaction();

        foreach($products as $product) {
            $transaction->append([
                'name' => $product->getName(),
                'brand' => $product->getBrand(),
                'position' => $product->getPosition(),
                'query' => $product->getQuery()
            ]);

            if ($transaction->count() > self::PAGE_SIZE) {
                $transaction->commit();

                $transaction = $this->createTransaction();
            }
        }

        if ($transaction->hasData()) {
            $transaction->commit();
        } else {
            $transaction->rollback();
        }
    }
}
