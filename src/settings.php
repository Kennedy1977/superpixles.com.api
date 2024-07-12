<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    $container->set('settings', function () {
        return [
            'db' => [
                'host' => $_ENV['DB_HOST'],
                'dbname' => $_ENV['DB_DATABASE'],
                'user' => $_ENV['DB_USERNAME'],
                'pass' => $_ENV['DB_PASSWORD'],
            ],
        ];
    });
};

