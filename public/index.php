<?php
declare(strict_types=1);

use App\Application\Handlers\HttpErrorHandler;
use App\Application\Handlers\ShutdownHandler;
use App\Application\ResponseEmitter\ResponseEmitter;
use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;
use Slim\Middleware\RoutingMiddleware;
use Slim\Middleware\ErrorMiddleware;

require __DIR__ . '/../vendor/autoload.php';

// Instantiate PHP-DI Container
$container = new Container();

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();

// Set up settings
$settings = require __DIR__ . '/../app/settings.php';
$settings($app);

// Set up dependencies
$dependencies = require __DIR__ . '/../app/dependencies.php';
$dependencies($app);

// Register middleware
$middleware = require __DIR__ . '/../app/middleware.php';
$middleware($app);

// Set up repositories
$repositories = require __DIR__ . '/../app/repositories.php';
$repositories($app);

// Register routes
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

/** @var bool $displayErrorDetails */
$displayErrorDetails = $container->get('settings')['displayErrorDetails'];

// Create Request object from globals
$serverRequestCreator = ServerRequestCreatorFactory::create();
$request = $serverRequestCreator->createServerRequestFromGlobals();

// Create Error Handler
$responseFactory = $app->getResponseFactory();
$errorHandler = new HttpErrorHandler($responseFactory);

// Create Shutdown Handler
$shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
register_shutdown_function($shutdownHandler);

// Add Routing Middleware
$routeResolver = $app->getRouteResolver();
$routingMiddleware = new RoutingMiddleware($routeResolver);
$app->add($routingMiddleware);

// Add Error Middleware
$callableResolver = $app->getCallableResolver();
$errorMiddleware = new ErrorMiddleware($callableResolver, $responseFactory, $displayErrorDetails, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);
$app->add($errorMiddleware);

// Run App & Emit Response
$response = $app->handle($request);
$responseEmitter = new ResponseEmitter();
$responseEmitter->emit($response);
