<?php
declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\ActionPayload;
use App\Domain\User\User;
use App\Service\User\ListUserService;
use DI\Container;
use Tests\TestCase;

class ListUserActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $user = new User(1, 'bill.gates', 'Bill', 'Gates');

        $listUserServiceProphecy = $this->prophesize(ListUserService::class);
        $listUserServiceProphecy
            ->getAllUsers()
            ->willReturn([$user])
            ->shouldBeCalledOnce();

        $container->set(ListUserService::class, $listUserServiceProphecy->reveal());

        $request = $this->createRequest('GET', '/users');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, [$user]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
