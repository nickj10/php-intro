<?php
declare(strict_types=1);

use SallePW\SlimApp\Controller\HomeController;
use SallePW\SlimApp\Middleware\BeforeMiddleware;
use SallePW\SlimApp\Middleware\StartSessionMiddleware;

$app->add(StartSessionMiddleware::class);

$app->get('/', HomeController::class . ':apply')->setName('home')
    ->add(BeforeMiddleware::class);