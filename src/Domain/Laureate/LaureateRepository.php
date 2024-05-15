<?php

declare(strict_types=1);

namespace App\Domain\Laureate;

interface LaureateRepository 
{
    /**
     * @return Laureate[]
     */
    public function findAll(): array;

    /**
     * @param int $id
     * @return Laureate
     * @throws LaureateNotFoundException
     */
    public function findLaureateOfId(int $id): Laureate;
}
