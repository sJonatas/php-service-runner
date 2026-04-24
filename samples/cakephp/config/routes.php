<?php

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes) {
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder) {
        $builder->post('/users', ['controller' => 'Users', 'action' => 'add']);
        $builder->get('/users', ['controller' => 'Users', 'action' => 'index']);
    });
};
