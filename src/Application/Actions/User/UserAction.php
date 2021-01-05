<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action as parentAlias;
use App\Domain\Settings\SettingsInterface;
use App\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;

abstract class UserAction extends parentAlias
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @param LoggerInterface $logger
     * @param SettingsInterface $settings
     * @param UserRepository $userRepository
     */
    public function __construct(LoggerInterface $logger,
                                SettingsInterface $settings,
                                UserRepository $userRepository
    ) {
        parent::__construct($logger, $settings);
        $this->userRepository = $userRepository;
    }
}
