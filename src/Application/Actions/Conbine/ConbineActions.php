<?php

namespace App\Application\Actions\Conbine;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Actions;
use App\Domain\Groups\GroupsServices;
use App\Domain\Orders\OrdersServices;
use App\Domain\Families\FamiliesServices;
use App\Domain\Species\SpeciesService;
use App\Domain\Creatures\CreaturesServices;
abstract class ConbineActions extends Actions
{
    protected $groupService;
    protected $orderService;
    protected $familyServices;
    protected $speciesServices;
    protected $creaturesServices;

    public function __construct(CreaturesServices $creaturesServices, LoggerInterface $logger, GroupsServices $groupService, OrdersServices $orderService, FamiliesServices $familyServices, SpeciesService $speciesServices)
    {
        parent::__construct($logger);
        $this->groupService = $groupService;
        $this->orderService = $orderService;
        $this->familyServices = $familyServices;
        $this->speciesServices = $speciesServices;
        $this->creaturesServices = $creaturesServices;
    }
}
