<?php
declare(strict_types=1);

use DI\Container;
use Slim\Views\Twig;

$container = new Container();

$container->set(
    'view',
    function () {
        return Twig::create(__DIR__ . '/../templates', ['cache' => false]);
    }
);

$container->set(
    HomeController::class,
    function (ContainerInterface $c) {
        $controller = new HomeController($c->get('view'));
        return $controller;
    }
);