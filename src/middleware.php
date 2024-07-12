<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function (App $app) {
    $app->add(function (Request $request, Response $response, callable $next) {
        $response = $next($request, $response);
        return $response;
    });
};

