<?php

declare(strict_types=1);

namespace App\Command\Stages;

use App\Service\WbProducts\Converter\WbProductsConverterInterface;
use Cake\Console\ConsoleIo;

/**
 * Стадия конвертации полученных данных с search.wb.ru.
 */
class StageConversion implements StageInterface
{
    /**
     * @param WbProductsConverterInterface $converter
     * @param ConsoleIo $io
     */
    public function __construct(private WbProductsConverterInterface $converter, private ConsoleIo $io)
    {
    }

    /**
     * @param array|\Generator $flow
     * @return \Generator
     */
    public function __invoke(array|\Generator $flow = []): \Generator
    {
        $batchProducts = [];
        foreach($flow as $page => $parseContent) {
            $batchProducts = array_merge(
                $batchProducts,
                $this->converter->convert($parseContent, $page * 100)
            );
            $this->io->info(sprintf("Collect %d products", count($batchProducts)));
        }

        yield $batchProducts;
    }
}
