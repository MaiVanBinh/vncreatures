<?php

namespace App\Application\Actions\User;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Actions;
use App\Domain\User\UserServices;

abstract class UserAction extends Actions {
    /**
     * @var UserServices
     */
    protected $userServices;

    /**
     * @param UserServices
     * @param LoggerInterface
     * @return void
     */
    public function __construct(LoggerInterface $logger, UserServices $userServices)
    {
        parent::__construct($logger);
        $this->userServices = $userServices;
    }
} 