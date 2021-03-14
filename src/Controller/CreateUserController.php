<?php
declare(strict_types=1);

namespace SallePW\SlimApp\Controller;

use DateTime;
use Exception;
use Slim\Views\Twig;
use SallePW\SlimApp\Model\User;
use SallePW\SlimApp\Model\UserRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class CreateUserController
{

    public function __construct(
        private Twig $twig,
        private UserRepository $userRepository
    )
    {
    }

    public function apply(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();

            // TODO - Validate data before instantiating the user
            $user = new User(
                $data['email'] ?? '',
                $data['password'] ?? '',
                new DateTime(),
                new DateTime()
            );
            $this->container->get('user_repository')->save($user);
        } catch (Exception $exception) {
            // You could render a .twig template here to show the error
            $response->getBody()
                ->write('Unexpected error: ' . $exception->getMessage());
            return $response->withStatus(500);
        }

        return $response->withStatus(201);
    }
}