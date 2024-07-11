<?php

return function ($app) {
    $container = $app->getContainer();

    $container->set('settings', function () {
        return [
            'db' => [
                'host' => $_ENV['DB_HOST'],
                'dbname' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USER'],
                'pass' => $_ENV['DB_PASS'],
            ],
        ];
    });
};
