<?php

namespace App;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Configures the routes
 * @param App $app
 */
class Routes
{
    /**
     * Configures the routes
     * @param App $app
     */
    public static function init(App $app)
    {
        $container = $app->getContainer();

        $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
            // Sample log message
            $container->get('logger')->info("Slim-Skeleton '/' route");

            // Render index view
            return $container->get('renderer')->render($response, 'index.phtml', $args);
        });
    }
}
