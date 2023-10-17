<?php

declare(strict_types=1);

namespace App\Service\WbProducts\Parser\Client;

use Cake\Http\Client\Request;

class WbSearchRequest extends Request
{
    private const BASE_URL = 'https://search.wb.ru/';

    private const SEARCH_URI = 'exactmatch/ru/common/v4/search?';

    private const BASE_QUERY_PARAMS = [
        'TestGroup' => 'no_test',
        'TestID' => 'no_test',
        'appType' => 1,
        'curr' => 'rub',
        'dest' => -1255942,
        'regions' => '80,38,4,64,83,33,68,70,69,30,86,75,40,1,66,110,22,31,48,71,114',
        'resultset' => 'catalog',
        'sort' => 'popular',
        'spp' => 0,
        'suppressSpellcheck' => 'false'
    ];

    /**
     * @param string $url
     * @param string $method
     * @param array $headers
     * @param $data
     */
    private function __construct(string $url = '', string $method = self::METHOD_GET, array $headers = [], $data = null)
    {
        parent::__construct($url, $method, $headers, $data);
    }

    /**
     * @param string $query
     * @return WbSearchRequest
     */
    public static function fromQueryString(string $query): WbSearchRequest
    {
        $params = http_build_query(
            array_merge(self::BASE_QUERY_PARAMS, ['query' => $query])
        );

        $url = sprintf('%s%s%s', self::BASE_URL, self::SEARCH_URI, $params);

        return new self($url, 'GET');
    }
}
