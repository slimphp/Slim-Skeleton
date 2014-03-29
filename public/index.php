<?php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);

date_default_timezone_set('America/Bahia');

// Setting constant
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('MODELS', ROOT . DS . 'app' . DS . 'models');
define('ROUTES', ROOT . DS . 'app' . DS . 'routes');
define('TEMPLATE_DEFAULT', ROOT . DS . 'app' . DS . 'template' . DS . 'default');
define('LOGS', ROOT . DS . 'logs');

$composer_autoload = ROOT . DS . 'vendor' . DS . 'autoload.php';

if ( ! file_exists($composer_autoload)) {
    die('Install composer.');
}

require $composer_autoload;

// php-activerecord
\ActiveRecord\Config::initialize(function($cfg)
{
    $cfg->set_model_directory( MODELS );
    $cfg->set_connections(array(
        'development' => 'mysql://username:password@localhost/database_name'
    ));
});

// slim-framework
$app = new \Slim\Slim(array(
    'templates.path' => TEMPLATE_DEFAULT
));

// Create monolog logger and store logger in container as singleton 
// (Singleton resources retrieve the same log resource definition each time)
$app->container->singleton('log', function ()
{
    $Logger = new \Monolog\Logger('slim-skeleton-full');
    $Logger->pushHandler(new \Monolog\Handler\StreamHandler( LOGS , \Monolog\Logger::DEBUG));

    return $Logger;
});

// Prepare view
$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = array(
    'charset' => 'utf-8',
    'cache' => TEMPLATE_DEFAULT . DS . 'cache',
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true
);

$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());

// Include routes
include ROUTES . DS . 'default.php';

// Run app
$app->run();