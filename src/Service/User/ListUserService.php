<?php
declare(strict_types=1);

namespace App\Service\User;

interface ListUserService
{
    /**
     * @return array
     */
    public function getAllUsers(): array;
}