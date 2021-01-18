<?php
declare(strict_types=1);

use App\Service\User\Implementation\ListUserServiceImpl;
use App\Service\User\Implementation\ViewUserServiceImpl;
use App\Service\User\ListUserService;
use App\Service\User\ViewUserService;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our user service interface to its in service implementation
    $containerBuilder->addDefinitions([
        ListUserService::class => \DI\autowire(ListUserServiceImpl::class),
        ViewUserService::class => \DI\autowire(ViewUserServiceImpl::class),
    ]);
};
