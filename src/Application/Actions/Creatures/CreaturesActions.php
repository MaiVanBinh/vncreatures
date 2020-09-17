<?php

namespace App\Application\Actions\Creatures;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Actions;
use App\Domain\Creatures\CreaturesServices;
use App\Domain\Assets\AssetsServices;

abstract class CreaturesActions extends Actions
{
    protected $creaturesServices;
    protected $assetsServices;

    public function __construct(LoggerInterface $logger, CreaturesServices $creaturesServices, AssetsServices $assetsServices)
    {
        parent::__construct($logger);
        $this->creaturesServices = $creaturesServices;
        $this->assetsServices = $assetsServices;
    }
}
