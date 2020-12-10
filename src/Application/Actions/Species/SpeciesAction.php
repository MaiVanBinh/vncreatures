<?php

namespace App\Application\Actions\Species;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Actions;
use App\Domain\Species\SpeciesService;
use App\Application\Actions\Validator;
use App\Domain\User\UserServices;
use App\Domain\Groups\GroupsServices;

use Exception;

abstract class SpeciesAction extends Actions{
    /**
     * @var SpeciesService
     */
    protected $speciesService;
    protected $validator;
    protected $userServices;
    protected $groupServices;
    /**
     * Construct
     * @param LoggerInterface
     * @param SpeciesService
     * 
     * @return Void
     */
    public function __construct(LoggerInterface $logger, SpeciesService $speciesService, Validator $validator, UserServices $userServices, GroupsServices $groupServices)
    {
        parent::__construct($logger);
        $this->speciesService = $speciesService;
        $this->validator = $validator;
        $this->userServices = $userServices;
        $this->groupServices = $groupServices;
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