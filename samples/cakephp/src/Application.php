<?php

declare(strict_types=1);

namespace App;

use App\Config\AppServiceConfig;
use Cake\Core\ContainerInterface;
use Cake\Http\BaseApplication;
use Cake\Http\MiddlewareQueue;
use Cake\Routing\Middleware\RoutingMiddleware;
use Cake\Routing\Router;
use ServiceRunner\Framework\CakePHP\ServiceRunnerProvider;
use ServiceRunner\ServiceConfig;

class Application extends BaseApplication
{
    public function bootstrap(): void
    {
        parent::bootstrap();
        Router::reload();
    }

    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue->add(new RoutingMiddleware($this));

        return $middlewareQueue;
    }

    public function services(ContainerInterface $container): void
    {
        $container->add(ServiceConfig::class, AppServiceConfig::class);
        $container->addServiceProvider(new ServiceRunnerProvider());
    }
}
