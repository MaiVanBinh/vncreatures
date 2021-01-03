<?php

namespace App\Application\Actions\User;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Actions;
use App\Domain\User\UserServices;
use App\Application\Actions\Validator;
use Exception;

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
    public function checkUserIsSuperAdmin($id) {
        try {
            $user = $this->userServices->checkUserIsSuperAdmin($id);
            if(count($user) > 0) {
                return true;
            } else {
                return false;
            }
        } catch(Exception $ex) {
            throw $ex->getMessage();
        }
    }
    public function checkUserExist($id) {
        try {
            $user = $this->userServices->findUserById($id);
            if(count($user) > 0) {
                return true;
            } else {
                return false;
            }
        } catch(Exception $ex) {
            throw $ex->getMessage();
        }
    }
}
