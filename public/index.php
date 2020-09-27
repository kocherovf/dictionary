<?php

declare(strict_types=1);

use DI\Bridge\Slim\Bridge;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

require '../vendor/autoload.php';

$app = Bridge::create();

$app->get(
    '/test',
    function (RequestInterface $request, ResponseInterface $response) {
        $response->getBody()->write('Ok!');
        return $response;
    }
);

$app->run();
