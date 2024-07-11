<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;

return function (App $app) {
    $app->get('/api/users', function (Request $request, Response $response) {
        try {
            $sql = "SELECT * FROM users";
            $stmt = $this->get('db')->query($sql);
            $users = $stmt->fetchAll();

            $response->getBody()->write(json_encode($users));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            throw new HttpInternalServerErrorException($request, $e->getMessage());
        }
    });

    $app->get('/api/user/{id}', function (Request $request, Response $response, array $args) {
        $id = $args['id'];
        try {
            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->get('db')->prepare($sql);
            $stmt->execute(['id' => $id]);
            $user = $stmt->fetch();

            if (!$user) {
                $response->getBody()->write(json_encode(['error' => 'User not found']));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            $response->getBody()->write(json_encode($user));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            throw new HttpInternalServerErrorException($request, $e->getMessage());
        }
    });
};
