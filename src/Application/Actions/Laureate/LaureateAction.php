<?php

declare(strict_types=1);

namespace App\Application\Actions\Laureate;

use App\Application\Actions\Action;
use App\Domain\Laureate\LaureateRepository;
use Psr\Log\LoggerInterface;

abstract class LaureateAction extends Action
{
    protected LaureateRepository $laureateRepository;

    public function __construct(LoggerInterface $logger, LaureateRepository $laureateRepository)
    {
        parent::__construct($logger);
        $this->laureateRepository = $laureateRepository;
    }
}
