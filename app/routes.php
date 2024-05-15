<?php

declare(strict_types=1);

use App\Application\Actions\Laureate\ListLaureatesAction;
use App\Application\Actions\Laureate\ViewLaureateAction;
use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $laureatesUrl = $request->getUri() . 'laureates';
        $body = "Click <a style='text-decoration:none;' href='$laureatesUrl'>here</a> for all laureates";
        $response->getBody()->write($body);
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    $app->group('/laureates', function (Group $group) {
        $group->get('', ListLaureatesAction::class);
        $group->get('/{id}', ViewLaureateAction::class);
    });
};
