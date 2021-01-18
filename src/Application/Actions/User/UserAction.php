<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Service\User\ListUserService;
use App\Service\User\ViewUserService;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{

    /**
     * @var ListUserService
     */
    protected $listUserService;

    /**
     * @var ViewUserService
     */
    protected $viewUserService;

    /**
     * @param LoggerInterface $logger
     * @param ListUserService $listUserService
     * @param ViewUserService $viewUserService
     */
    public function __construct(
        LoggerInterface $logger,
        ListUserService $listUserService,
        ViewUserService $viewUserService
    ) {
        parent::__construct($logger);
        $this->listUserService = $listUserService;
        $this->viewUserService = $viewUserService;
    }
}
