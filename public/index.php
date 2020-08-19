<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use App\Application\Handlers\HttpErrorHandler;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

// Set up settings
$settings = require __DIR__ . '/../config/settings.php';
$settings($containerBuilder);

// Set up dependencies
$dependencies = require __DIR__ . '/../config/dependencies.php';
$dependencies($containerBuilder);

// build container
$container = $containerBuilder->build();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();

// add middleware
$middleware = require __DIR__ . ('/../config/middleware.php');
$middleware($app);

// add router
$routes = require __DIR__ . ('/../config/routes.php');
$routes($app);

/** @var bool $displayErrorDetails */
$displayErrorDetails = $container->get('settings')['displayErrorDetails'];

// Create Request object from globals
// $serverRequestCreator = ServerRequestCreatorFactory::create();
// $request = $serverRequestCreator->createServerRequestFromGlobals();

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

// Create Shutdown Handler
// $shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
// register_shutdown_function($shutdownHandler);

// Add Routing Middleware
$app->addRoutingMiddleware();

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

$app->run();
// Run App & Emit Response
// $response = $app->handle($request);
// $responseEmitter = new ResponseEmitter();
// $responseEmitter->emit($response);
