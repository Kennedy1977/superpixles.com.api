<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Exception\HttpNotFoundException;
use Slim\Handlers\ErrorHandler;

return function (App $app) {
    $container = $app->getContainer();

    // Custom 404 handler
    $notFoundHandler = function (
        Request $request,
        Throwable $exception,
        bool $displayErrorDetails,
        bool $logErrors,
        bool $logErrorDetails
    ) use ($app): Response {
        $response = new \Slim\Psr7\Response();
        $response->getBody()->write('Oh no! page not found!');
        return $response->withStatus(404)->withHeader('Content-Type', 'text/html');
    };

    // Add the custom error handler
    $app->addRoutingMiddleware();

    $errorMiddleware = $app->addErrorMiddleware(true, true, true);
    $errorMiddleware->setErrorHandler(HttpNotFoundException::class, $notFoundHandler);
};
