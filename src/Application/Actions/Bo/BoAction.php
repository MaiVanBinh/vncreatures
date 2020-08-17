<?php

namespace App\Application\Actions\Bo;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Actions;
use App\Domain\Bo\BoServices;

abstract class BoAction extends Actions{
    protected $services;

    public function __construct(LoggerInterface $logger, BoServices $services)
    {
        parent::__construct($logger);
        $this->services = $services;    
    }
}