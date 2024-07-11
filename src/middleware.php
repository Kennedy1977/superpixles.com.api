<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;

return function (App $app) {
    $app->add(function (Request $request, RequestHandler $handler): Response {
        $token = $request->getHeaderLine('Authorization');

        // Simple token validation (for demonstration purposes)
        if ($token !== 'Bearer your-secret-token') {
            $response = new \Slim\Psr7\Response();
            $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    });
};
