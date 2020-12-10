<?php

namespace App\Application\Actions\Families;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Actions;
use App\Application\Actions\Validator;
use App\Domain\Families\FamiliesServices;
use App\Domain\User\UserServices;
use App\Domain\Creatures\CreaturesServices;
use Exception;

abstract class FamiliesAction extends Actions{
    protected $familiesServices;
    protected $validator;
    protected $userServices;
    protected $creaturesService;

    public function __construct(LoggerInterface $logger, FamiliesServices $familiesServices,UserServices $userServices, CreaturesServices $creaturesService, Validator $validator)
    {
        parent::__construct($logger);
        $this->familiesServices = $familiesServices;
        $this->userServices = $userServices;
        $this->creaturesService = $creaturesService;
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