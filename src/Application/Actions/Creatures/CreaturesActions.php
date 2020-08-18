<?php

namespace App\Application\Actions\Creatures;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Actions;
use App\Domain\Creatures\CreaturesServices;

abstract class CreaturesActions extends Actions{
    protected $services;

    public function __construct(LoggerInterface $logger, CreaturesServices $services)
    {
        parent::__construct($logger);
        $this->services = $services;    
    }
}