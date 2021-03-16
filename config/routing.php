<?php

declare(strict_types=1);

use SallePW\SlimApp\Controller\HomeController;
use SallePW\SlimApp\Controller\VisitsController;
use SallePW\SlimApp\Controller\CookieMonsterController;
use SallePW\SlimApp\Controller\CreateUserController;
use SallePW\SlimApp\Controller\SimpleFormController;
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

$app->post(
    '/user',
    CreateUserController::class . ":apply"
)->setName('create_user');

$app->get(
    '/simple-form',
    SimpleFormController::class . ":showForm"
);

$app->post(
    '/simple-form',
    SimpleFormController::class . ":handleFormSubmission"
)->setName('handle-form');
