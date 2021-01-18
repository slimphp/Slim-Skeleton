<?php
declare(strict_types=1);

namespace App\Service\User;

use App\Domain\User\User;

interface ViewUserService
{
    /**
     * @param int $id
     * @return User
     */
    public function getUserById(int $id): User;
}