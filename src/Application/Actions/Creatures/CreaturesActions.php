<?php

namespace App\Application\Actions\Creatures;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Actions;
use App\Domain\Creatures\CreaturesServices;
use App\Domain\Assets\AssetsServices;
use App\Domain\User\UserServices;
use App\Domain\AssetsCreaturesServices;

abstract class CreaturesActions extends Actions
{
    protected $creaturesServices;
    protected $assetsServices;
    protected $userServices;
    protected $acServices;
    public function __construct(
        LoggerInterface $logger, 
        CreaturesServices $creaturesServices, 
        AssetsServices $assetsServices, 
        UserServices $userServices,
        AssetsCreaturesServices $acServices
        )
    {
        parent::__construct($logger);
        $this->creaturesServices = $creaturesServices;
        $this->assetsServices = $assetsServices;
        $this->userServices = $userServices;
        $this->acServices = $acServices;
    }
}
