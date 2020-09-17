<?php

namespace App\Application\Actions\Family;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Actions;
use App\Domain\Ho\FamilyServices;

abstract class FamilyAction extends Actions{
    protected $services;

    public function __construct(LoggerInterface $logger, FamilyServices $services)
    {
        parent::__construct($logger);
        $this->services = $services;
    }
}