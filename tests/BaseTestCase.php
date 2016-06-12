<?php

namespace Tests;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Use middleware when running application?
     *
     * @var bool
     */
    protected $withMiddleware = true;

    /**
     * Process the application given a request method and URI
     *
     * @param string $requestMethod the request method (e.g. GET, POST, etc.)
     * @param string $requestUri the request URI
     * @return \Slim\Http\Response
     */
    public function runApp($requestMethod, $requestUri)
    {
        // Create a mock environment for testing with
        $environment = \Slim\Http\Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri
            ]
        );

        // Set up a request object based on the environment
        $request = \Slim\Http\Request::createFromEnvironment($environment);

        // Set up a response object
        $response = new \Slim\Http\Response();

        // Use the application settings
        $settings = require __DIR__ . '/../src/settings.php';

        // Instantiate the application
        $app = new \Slim\App($settings);

        // Set up dependencies
        require __DIR__ . '/../src/dependencies.php';

        // Register middleware
        if ($this->withMiddleware) {
            require __DIR__ . '/../src/middleware.php';
        }

        // Register routes
        require __DIR__ . '/../src/routes.php';

        // Process the application
        $response = $app->process($request, $response);

        // Return the response
        return $response;
    }
}