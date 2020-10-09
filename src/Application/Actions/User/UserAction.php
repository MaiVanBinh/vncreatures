<?php

namespace App\Application\Actions\User;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Actions;
use App\Domain\User\UserServices;
use App\Application\Actions\Validator;

abstract class UserAction extends Actions
{
    /**
     * @var UserServices
     */
    protected $userServices;

    protected $validator;

    /**
     * @param UserServices
     * @param LoggerInterface
     * @return void
     */
    public function __construct(LoggerInterface $logger, UserServices $userServices, Validator $validator)
    {
        parent::__construct($logger);
        $this->userServices = $userServices;
        $this->validator = $validator;
    }
}
