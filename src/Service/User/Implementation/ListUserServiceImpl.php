<?php
declare(strict_types=1);

namespace App\Service\User\Implementation;

use App\Domain\User\UserRepository;
use App\Service\User\ListUserService;
use Psr\Log\LoggerInterface;

class ListUserServiceImpl implements ListUserService
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * ListUserServiceImpl constructor.
     * @param LoggerInterface $logger
     * @param UserRepository $userRepository
     */
    public function __construct(LoggerInterface $logger, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getAllUsers(): array
    {
        $users = $this->userRepository->findAll();

        $this->logger->info("Users list was viewed.");

        return $users;
    }
}