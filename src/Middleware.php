<?php

namespace App;

use Slim\App;

class Middleware
{
    /**
     * Configures the middlewares
     * @param App $app
     */
    public static function init(App $app)
    {
        // e.g: $app->add(new \Slim\Csrf\Guard);
    }
}
