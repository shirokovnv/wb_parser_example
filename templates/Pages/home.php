<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.10.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Exception\NotFoundException;

$this->disableAutoLayout();

$checkConnection = function (string $name) {
    $error = null;
    $connected = false;
    try {
        $connection = ConnectionManager::get($name);
        $connected = $connection->connect();
    } catch (Exception $connectionError) {
        $error = $connectionError->getMessage();
        if (method_exists($connectionError, 'getAttributes')) {
            $attributes = $connectionError->getAttributes();
            if (isset($attributes['message'])) {
                $error .= '<br />' . $attributes['message'];
            }
        }
        if ($name === 'debug_kit') {
            $error = 'Try adding your current <b>top level domain</b> to the
                <a href="https://book.cakephp.org/debugkit/4/en/index.html#configuration" target="_blank">DebugKit.safeTld</a>
            config and reload.';
            if (!in_array('sqlite', \PDO::getAvailableDrivers())) {
                $error .= '<br />You need to install the PHP extension <code>pdo_sqlite</code> so DebugKit can work properly.';
            }
        }
    }

    return compact('connected', 'error');
};

if (!Configure::read('debug')) :
    throw new NotFoundException(
        'Please replace templates/Pages/home.php with your own version or re-enable debug mode.'
    );
endif;

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        CakePHP: the rapid development PHP framework:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css(['normalize.min', 'milligram.min', 'fonts', 'cake', 'home']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
    <header>
        <div class="container text-center">
            <a href="/" target="_blank" rel="noopener">
                <img alt="EggHeads Wildberries Parser" src="/img/logo.png" width="350" />
            </a>
            <h1>
                Парсер товаров Wildberries
            </h1>
        </div>
    </header>
    <main class="main">
        <div class="container">
            <div class="content">
                <div class="row">
                    <div class="column">
                        <h3>Принцип работы:</h3>
                        <ol>
                            <li>Нужно попасть в shell докер-контейнера с php-приложением c помощью: <strong>make shell</strong></li>
                            <li>Выполнить команду <strong>bin/cake click_house_init</strong> для создания таблицы ClickHouse</li>
                            <li>Выполнить команду
                                <strong>bin/cake parse_products "[ПОИСКОВАЯ ФРАЗА]"</strong> - она парсит 1000 записей (10 страниц по 100)
                                с эндпоинта <em>search.wb.ru</em> и сохраняет данные в <strong>ClickHouse</strong>
                            </li>
                            <li>Скрипт выведет кол-во сохраненных записей, либо ошибку</li>
                            <li>Далее <a href="/wbSearch">Перейти к форме поиска</a></li>
                            <li>Ввести ту же поисковую фразу и нажать <strong>Enter</strong></li>
                            <li>Если данные были успешно сохранены ранее, то они отобразятся в табличном виде</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
