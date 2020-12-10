<?php

namespace App\Application\Actions\Orders;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Actions;
use App\Domain\Orders\OrdersServices;
use App\Application\Actions\Validator;
use App\Domain\User\UserServices;
use App\Domain\Families\FamiliesServices;
use Exception;

abstract class OrdersActions extends Actions{
    protected $orderServices;
    protected $validator;
    protected $userServices;
    protected $familiesServices;
    public function __construct(LoggerInterface $logger, OrdersServices $orderServices, UserServices $userServices, Validator $validator, FamiliesServices $familiesServices)
    {
        parent::__construct($logger);
        $this->orderServices = $orderServices;   
        $this->userServices = $userServices; 
        $this->validator = $validator;
        $this->familiesServices = $familiesServices;
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