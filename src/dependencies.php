<?php

use DI\Container;
use Psr\Container\ContainerInterface;
use Slim\App;
use Tuupola\Middleware\JwtAuthentication;

return function (App $app) {
    $container = $app->getContainer();

    // Database connection
    $container->set('db', function (ContainerInterface $c) {
        $db = $c->get('settings')['db'];
        $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    });

    // JWT Middleware
    $app->add(new JwtAuthentication([
        "path" => ["/user"],
        "secret" => $_ENV['JWT_SECRET']
    ]));
};

