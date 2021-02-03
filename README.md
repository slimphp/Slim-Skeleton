# Slim Framework 4 Skeleton Application

[![Coverage Status](https://coveralls.io/repos/github/slimphp/Slim-Skeleton/badge.svg?branch=master)](https://coveralls.io/github/slimphp/Slim-Skeleton?branch=master)

Use this skeleton application to quickly setup and start working on a new Slim Framework 4 application. This application uses the latest Slim 4 with Slim PSR-7 implementation and PHP-DI container implementation. It also uses the Monolog logger.

This skeleton application was built for Composer. This makes setting up a new Slim Framework application quick and easy.

## Install the Application

Run this command from the directory in which you want to install your new Slim Framework application.

```bash
composer create-project slim/slim-skeleton [my-app-name]
```

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writable.

To run the application in development, you can run these commands 

```bash
cd [my-app-name]
composer start
```

Or you can use `docker-compose` to run the app with `docker`, so you can run these commands:
```bash
cd [my-app-name]
docker-compose up -d
```
After that, open `http://localhost:8080` in your browser.

Run this command in the application directory to run the test suite

```bash
composer test
```

## PHP-Viewer

#### How to install PHP-Viewer?
```bash
composer require slim/php-view
```

#### How to use PHP-Viewer?

public\index.php
```php

...

use Slim\Views\PhpRenderer;
use App\Application\Controller\IndexController;

require __DIR__ . '/../vendor/autoload.php';

...

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Set PHP-View in container
$container->set('phpView', function() : PhpRenderer {
    return new PhpRenderer(__DIR__ . '/../templates');
});

// Set IndexController in container
$container->set('IndexController', function (ContainerInterface $container) : IndexController {
	return new IndexController($container); 
});

// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();

...

```

app\routes.php
```php
...

$app->get('/welcome[/{name:[a-zA-z]+}]', IndexController::class . ':index');

...
```

src\Application\Controller\IndexController.php
```php
<?php

namespace App\Application\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface as Container;

class IndexController
{
    private $container;
    private $phpView;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->phpView  = $container->get('phpView');
    }

    public function index(Request $request, Response $response, array $args) : Response
    {
        $response = $this->phpView->render($response, "index.phtml", $args);

        return $response;
    }
}
```

templates\index.phtml
```phtml
<!DOCTYPE html>
<html>
    <head>
        <!-- HEAD -->
    </head>
    <body>
        <!-- BODY -->
    </body>
</html>
```

#### Examples

Please see: https://github.com/AntoninoM90/Slim-Skeleton/tree/PHP-Viewer

##

That's it! Now go build something cool.
