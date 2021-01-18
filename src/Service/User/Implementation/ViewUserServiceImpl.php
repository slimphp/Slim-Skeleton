<?php
declare(strict_types=1);

namespace App\Service\User\Implementation;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use App\Service\User\ViewUserService;
use Psr\Log\LoggerInterface;

class ViewUserServiceImpl implements ViewUserService
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
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function getUserById(int $id): User
    {
        try {
            $user = $this->userRepository->findUserOfId($id);
        } catch (UserNotFoundException $e) {
            throw new UserNotFoundException();
        }

        $this->logger->info("User of id `${id}` was viewed.");

        return $user;
    }
}