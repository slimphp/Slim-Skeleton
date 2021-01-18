<?php
declare(strict_types=1);

namespace Tests\Service\User;

use App\Domain\User\User;
use App\Service\User\ListUserService;
use DI\Container;
use Tests\TestCase;

class ListUserServiceTest extends TestCase
{
    public function testListUserService()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $ListUserService = $container->get(ListUserService::class);

        $users = [
            1 => new User(1, 'bill.gates', 'Bill', 'Gates'),
            2 => new User(2, 'steve.jobs', 'Steve', 'Jobs'),
            3 => new User(3, 'mark.zuckerberg', 'Mark', 'Zuckerberg'),
            4 => new User(4, 'evan.spiegel', 'Evan', 'Spiegel'),
            5 => new User(5, 'jack.dorsey', 'Jack', 'Dorsey'),
        ];

        $this->assertEquals(array_values($users), $ListUserService->getAllUsers());
    }
}