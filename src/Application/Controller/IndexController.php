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
        // Your code here
        // ...

        $response = $this->phpView->render($response, "index.phtml", $args);

        return $response;
    }
}