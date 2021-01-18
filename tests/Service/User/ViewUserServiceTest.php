<?php
declare(strict_types=1);

namespace Tests\Service\User;

use App\Domain\User\User;
use App\Service\User\ViewUserService;
use DI\Container;
use Tests\TestCase;

class ViewUserServiceTest extends TestCase
{
    public function testGetUserById()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $viewUserService = $container->get(ViewUserService::class);

        $user = new User(1, 'bill.gates', 'Bill', 'Gates');

        $this->assertEquals($user, $viewUserService->getUserById(1));
    }
}