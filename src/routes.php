<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;
use DI\Container;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

return function (App $app) {
    // Public route to get users (for testing purposes)
    $app->get('/users', function (Request $request, Response $response) {
        $sql = "SELECT id, first_name, last_name, email, username, created_at FROM users";
        try {
            $db = $this->get('db');
            $stmt = $db->query($sql);
            $users = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            $response->getBody()->write(json_encode($users));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            $error = ['message' => $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });

    // Public route for login
    $app->post('/login', function (Request $request, Response $response) {
        $input = $request->getParsedBody();
        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';

        $sql = "SELECT id, password FROM users WHERE username = :username";
        try {
            $db = $this->get('db');
            $stmt = $db->prepare($sql);
            $stmt->bindParam('username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;

            if ($user && password_verify($password, $user->password)) {
                $issuedAt = time();
                $expirationTime = $issuedAt + 43200; // jwt valid for 12 hours from the issued time
                $payload = array(
                    'iat' => $issuedAt,
                    'exp' => $expirationTime,
                    'userId' => $user->id,
                );

                $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

                $data = ['token' => $token];
                $response->getBody()->write(json_encode($data));
                return $response->withHeader('Content-Type', 'application/json');
            } else {
                $error = ['message' => 'Invalid username or password'];
                $response->getBody()->write(json_encode($error));
                return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
            }
        } catch (PDOException $e) {
            $error = ['message' => $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });

    // Protected route to get user by ID
    $app->get('/user/{id}', function (Request $request, Response $response, $args) {
        $id = $args['id'];
        $sql = "SELECT id, first_name, last_name, email, username, created_at FROM users WHERE id = :id";
        try {
            $db = $this->get('db');
            $stmt = $db->prepare($sql);
            $stmt->bindParam('id', $id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;

            if ($user) {
                $response->getBody()->write(json_encode($user));
                return $response->withHeader('Content-Type', 'application/json');
            } else {
                $error = ['message' => 'User not found'];
                $response->getBody()->write(json_encode($error));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }
        } catch (PDOException $e) {
            $error = ['message' => $e->getMessage()];
            $response->getBody()->write(json_encode($error));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    })->add(new Tuupola\Middleware\JwtAuthentication([
        "path" => "/user",
        "secret" => $_ENV['JWT_SECRET']
    ]));
};

