<?php
declare(strict_types=1);

use SallePW\SlimApp\Controller\HomeController;
use SallePW\SlimApp\Controller\VisitsController;
use SallePW\SlimApp\Controller\CookieMonsterController;
use SallePW\SlimApp\Middleware\BeforeMiddleware;
use SallePW\SlimApp\Middleware\StartSessionMiddleware;

$app->add(StartSessionMiddleware::class);
$app->add(BeforeMiddleware::class);

$app->get('/', HomeController::class . ':apply')->setName('home');

$app->get(
    '/visits',
    VisitsController::class . ":showVisits"
)->setName('visits');

$app->get(
    '/cookies',
    CookieMonsterController::class . ":showAdvice"
)->setName('cookies');