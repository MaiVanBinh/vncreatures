<?php

namespace App\Application\Actions\Groups;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Validator;
use App\Application\Actions\Actions;
use App\Domain\Groups\GroupsServices;
use App\Domain\User\UserServices;
use App\Domain\Orders\OrdersServices;

use Exception;

abstract class GroupsAction extends Actions{
    protected $groupsServices;
    protected $validator;
    protected $userServices;
    protected $ordersServices;

    public function __construct(LoggerInterface $logger, Validator $validator, GroupsServices $groupsServices, UserServices $userServices, OrdersServices $ordersServices)
    {
        parent::__construct($logger);
        $this->groupsServices = $groupsServices;
        $this->validator = $validator;
        $this->userServices = $userServices;
        $this->ordersServices = $ordersServices;
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