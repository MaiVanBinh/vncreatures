<?php

namespace App\Application\Actions\Wood;
use App\Application\Actions\Actions;
use Psr\Log\LoggerInterface;
use App\Domain\Wood\WoodServices;
use App\Domain\User\UserServices;
use Exception;
use App\Application\Actions\Validator;

abstract class WoodAction extends Actions {
    protected $woodServices;
    protected $userServices;
    protected $validator;
    
    public function __construct(WoodServices $woodServices, LoggerInterface $logger, UserServices $userServices, Validator $validator) {
        parent::__construct($logger);
        $this->woodServices = $woodServices;
        $this->userServices = $userServices;
        $this->validator = $validator;
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