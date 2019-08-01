<?php
declare(strict_types=1);

namespace Tests;

use DI\Container;
use Exception;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Psr7\Body;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;

class TestCase extends PHPUnit_TestCase
{
    /**
     * @return App
     * @throws Exception
     */
    protected function getAppInstance(): App
    {
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

        return $app;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array  $headers
     * @param array  $serverParams
     * @param array  $cookies
     * @return Request
     */
    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $serverParams = [],
        array $cookies = []
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $headers = Headers::createFromGlobals($headers);
        $handle = fopen('php://temp', 'w+');
        $stream = new Body($handle);

        return new SlimRequest($method, $uri, $headers, $serverParams, $cookies, $stream);
    }
}
