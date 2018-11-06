<?php

namespace App;

use Slim\App;

class Dependencies
{
    /**
     * Configures the DI Container
     * @param App $app
     */
    public static function init(App $app)
    {
        $container = $app->getContainer();

        // view renderer
        $container['renderer'] = function ($c) {
            $settings = $c->get('settings')['renderer'];
            return new \Slim\Views\PhpRenderer($settings['template_path']);
        };

        // monolog
        $container['logger'] = function ($c) {
            $settings = $c->get('settings')['logger'];
            $logger = new \Monolog\Logger($settings['name']);
            $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
            $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
            return $logger;
        };
    }
}
