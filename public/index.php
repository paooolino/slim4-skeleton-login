<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set("Europe/Rome");
setlocale(LC_TIME, "it_IT");

// set container and dependencies
$containerBuilder = new \DI\ContainerBuilder();

require __DIR__ . '/../app/dependencies.php';

$container = $containerBuilder->build();
Slim\Factory\AppFactory::setContainer($container);

// Instantiate the Slim App
$app = Slim\Factory\AppFactory::create();
//$app->setBasePath('/subdirectory');

// Add Error Handling Middleware
$app->addErrorMiddleware(true, false, false);

// routing middleware
$app->addRoutingMiddleware();

// App middleware
require __DIR__ . '/../app/middleware.php';

// App routes
require __DIR__ . '/../app/routes.php';

// routing middleware
$app->addRoutingMiddleware();

$app->run();
