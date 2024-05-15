<?php

declare(strict_types=1);

namespace App\Domain\Laureate;

use App\Domain\DomainException\DomainRecordNotFoundException;

class LaureateNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The laureate you requested does not exist.';
}
