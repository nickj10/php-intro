<?php
declare(strict_types=1);

namespace SallePW\SlimApp\Controller;

use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

final class CookieMonsterController
{
    private $c;

    public function __construct(ContainerInterface $c)
    {
        $this->c = $c;
    }

    public function showAdvice(Request $request, Response $response): Response
    {
        $cookie = FigRequestCookies::get($request, 'cookies_advice', "0");

        $isAdvised = boolval($cookie->getValue());

        if (!$isAdvised) {
            $response = FigResponseCookies::set(
                $response,
                $this->generateAdviceCookie()
            );
        }

        return $this->c->get('view')->render(
            $response,
            'cookies.twig',
            [
                'isAdvised' => $isAdvised,
            ]
        );
    }

    private function generateAdviceCookie(): SetCookie
    {
        return SetCookie::create('cookies_advice')
            ->withValue("1")
            ->withDomain('localhost')
            ->withPath('/cookies');
    }
}