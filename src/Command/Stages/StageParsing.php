<?php

declare(strict_types=1);

namespace App\Command\Stages;

use App\Service\WbProducts\Parser\WbProductsParserInterface;

/**
 * Стадия разбора данных от поставщика search.wb.ru.
 */
class StageParsing implements StageInterface
{
    /**
     * @param WbProductsParserInterface $parser
     * @param string $userQuery
     */
    public function __construct(private WbProductsParserInterface $parser, private string $userQuery)
    {
    }

    /**
     * @param array|\Generator $flow
     * @return \Generator
     */
    public function __invoke(array|\Generator $flow = []): \Generator
    {
        foreach($flow as $page) {
            $response = $this->parser->parseByUserParams($this->userQuery, $page);

            yield $page => $response->getContent();
        }
    }
}
