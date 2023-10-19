<?php

declare(strict_types=1);

namespace App\Test\TestCase\Command\Stubs;

use App\Command\ClickHouseDropCommand;
use App\Command\ClickHouseInitCommand;
use App\Test\Mocks\MockClickHouseClient;
use Cake\Console\CommandCollection;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Http\BaseApplication;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\RouteBuilder;
use League\Container\ReflectionContainer;

/**
 * TODO: это хак!!! для тестирования команд с зависимостями в конструкторе.
 * Необходимо добавить нужные команды с mock-зависимостями в метод "console",
 * а также при тестирировании указать текущий namespace в методе "setUp" тест-кейса команды.
 */
class Application extends BaseApplication
{
    public function bootstrap(): void
    {
        parent::bootstrap();

        // Load plugins defined in Configure.
        if (Configure::check('Plugins.autoload')) {
            foreach (Configure::read('Plugins.autoload') as $value) {
                $this->addPlugin($value);
            }
        }

        // Check plugins added here
    }

    /**
     * @param CommandCollection $commands
     * @return CommandCollection
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        $mockClient = MockClickHouseClient::createInstanceForWriting();

        return $commands
            ->add('click_house_drop', new ClickHouseDropCommand($mockClient))
            ->add('click_house_init', new ClickHouseInitCommand($mockClient));
    }

    /**
     * @param MiddlewareQueue $middlewareQueue
     * @return MiddlewareQueue
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        return $middlewareQueue;
    }

    /**
     * Routes hook, used for testing with RoutingMiddleware.
     */
    public function routes(RouteBuilder $routes): void
    {
    }

    /**
     * Container register hook
     *
     * @param \Cake\Core\ContainerInterface $container The container to update
     */
    public function services(ContainerInterface $container): void
    {
        $container->delegate(new ReflectionContainer());
    }
}
