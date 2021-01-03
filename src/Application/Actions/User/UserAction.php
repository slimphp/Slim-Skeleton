<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Domain\User\UserRepository;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param LoggerInterface $logger
     * @param ContainerInterface $container
     * @param UserRepository $userRepository
     */
    public function __construct(LoggerInterface $logger, ContainerInterface $container, UserRepository $userRepository)
    {
        parent::__construct($logger, $container);
        $this->userRepository = $userRepository;
    }
}
