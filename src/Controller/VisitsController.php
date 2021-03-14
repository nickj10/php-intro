<?php
declare(strict_types=1);

namespace SallePW\SlimApp\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

final class VisitsController
{
    private $c;

    public function __construct(ContainerInterface $c)
    {
        $this->c = $c;
    }

    public function showVisits(Request $request, Response $response): Response
    {
        if (empty($_SESSION['counter'])) {
            $_SESSION['counter'] = 1;
        } else {
            $_SESSION['counter']++;
        }

        return $this->c->get('view')->render(
            $response,
            'visits.twig',
            [
                'visits' => $_SESSION['counter'],
            ]
        );
    }
}