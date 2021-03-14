<?php
declare(strict_types=1);

use DI\Container;
use Slim\Views\Twig;
use SallePW\SlimApp\Controller\HomeController;
use SallePW\SlimApp\Controller\VisitsController;
use Psr\Container\ContainerInterface;
use SallePW\SlimApp\Repository\MySQLUserRepository;
use SallePW\SlimApp\Repository\PDOSingleton;

$container = new Container();

$container->set(
    'view',
    function () {
        return Twig::create(__DIR__ . '/../templates', ['cache' => false]);
    }
);

$container->set('db', function () {
    return PDOSingleton::getInstance(
        $_ENV['MYSQL_USER'],
        $_ENV['MYSQL_ROOT_PASSWORD'],
        $_ENV['MYSQL_HOST'],
        $_ENV['MYSQL_PORT'],
        $_ENV['MYSQL_DATABASE']
    );
});

$container->set('user_repository', function (ContainerInterface $container) {
    return new MySQLUserRepository($container->get('db'));
});


$container->set(
    HomeController::class,
    function (ContainerInterface $c) {
        $controller = new HomeController($c->get('view'));
        return $controller;
    }
);

$container->set(
    VisitsController::class,
    function (ContainerInterface $c) {
        $controller = new VisitsController($c->get('view'));
        return $controller;
    }
);

$container->set(
    CookieMonsterController::class,
    function (ContainerInterface $c) {
        $controller = new CookieMonsterController($c);
        return $controller;
    }
);

$container->set(
    CreateUserController::class,
    function (ContainerInterface $c) {
        $controller = new CreateUserController($c);
        return $controller;
    }
);