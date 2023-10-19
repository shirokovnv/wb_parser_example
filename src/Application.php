<?php

declare(strict_types=1);

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
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App;

use App\Command\ClickHouseDropCommand;
use App\Command\ClickHouseInitCommand;
use App\Command\ParseProductsCommand;
use App\Model\Table\WbProductsClickhouseTable;
use App\Service\WbProducts\Converter\WbProductsConverterInterface;
use App\Service\WbProducts\Converter\WbProductsJsonConverter;
use App\Service\WbProducts\Exception\WbProductsExceptionHandler;
use App\Service\WbProducts\Parser\WbProductsParser;
use App\Service\WbProducts\Parser\WbProductsParserInterface;
use App\Service\WbProducts\Repository\WbProductsRepository;
use App\Service\WbProducts\Repository\WbProductsRepositoryInterface;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Client;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\Log\Engine\FileLog;
use Cake\Log\Log;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Eggheads\CakephpClickHouse\ClickHouse;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication
{
    /**
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
        }

        // Load more plugins here
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance.
            // See https://github.com/CakeDC/cakephp-cached-routing
            ->add(new RoutingMiddleware($this))

            // Parse various types of encoded request bodies so that they are
            // available as array through $request->getData()
            // https://book.cakephp.org/4/en/controllers/middleware.html#body-parser-middleware
            ->add(new BodyParserMiddleware())

            // Cross Site Request Forgery (CSRF) Protection Middleware
            // https://book.cakephp.org/4/en/security/csrf.html#cross-site-request-forgery-csrf-middleware
            ->add(new CsrfProtectionMiddleware([
                'httponly' => true,
            ]));

        return $middlewareQueue;
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/4/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
        // TODO: Возможно, лучшим решением было бы ограничить scope клиента, напр. new Client(['base_url' => 'search.wb.ru']);
        $container->add(ClientInterface::class, Client::class);
        $container->add(WbProductsParserInterface::class, fn () => new WbProductsParser($container->get(ClientInterface::class)));
        $container->add(WbProductsConverterInterface::class, WbProductsJsonConverter::class);
        $container->add(WbProductsRepositoryInterface::class, WbProductsRepository::class)
            ->addArgument(WbProductsClickhouseTable::getInstance());
        $container->add(WbProductsExceptionHandler::class)
            ->addArgument(Log::engine('error'));
        $container->add(ParseProductsCommand::class)
            ->addArguments(
                [
                    $container->get(WbProductsParserInterface::class),
                    $container->get(WbProductsConverterInterface::class),
                    $container->get(WbProductsRepositoryInterface::class),
                    $container->get(WbProductsExceptionHandler::class)
                ]
            );
        $container->add(ClickHouseInitCommand::class)
            ->addArgument(ClickHouse::getInstance()->getClient());
        $container->add(ClickHouseDropCommand::class)
            ->addArgument(ClickHouse::getInstance()->getClient());
    }

    /**
     * Bootstrapping for CLI application.
     *
     * That is when running commands.
     *
     * @return void
     */
    protected function bootstrapCli(): void
    {
        $this->addOptionalPlugin('Bake');

        $this->addPlugin('Migrations');

        // Load more plugins here
    }
}
