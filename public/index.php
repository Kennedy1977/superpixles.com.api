<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Create App
AppFactory::setContainer(new DI\Container());
$app = AppFactory::create();

// Set up settings
(require __DIR__ . '/../src/settings.php')($app);

// Set up dependencies
(require __DIR__ . '/../src/dependencies.php')($app);

// Register routes
(require __DIR__ . '/../src/routes.php')($app);

// Add middleware
(require __DIR__ . '/../src/middleware.php')($app);

// Register error handlers
(require __DIR__ . '/../src/error_handlers.php')($app);

// Run app
$app->run();
