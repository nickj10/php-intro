<?php
declare(strict_types=1);

namespace SallePW\SlimApp\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

final class HomeController
{
    private $c;

    // You can also use https://stitcher.io/blog/constructor-promotion-in-php-8
    public function __construct(ContainerInterface $c)
    {
        $this->c = $c;
    }

    public function apply(Request $request, Response $response)
    {
        return $this->c->get('view')->render($response, 'home.twig');
    }
}