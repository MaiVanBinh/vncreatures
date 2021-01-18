<?php

namespace App\Application\Actions\Wood;
use App\Application\Actions\Actions;
use Psr\Log\LoggerInterface;
use App\Domain\Wood\WoodServices;

abstract class WoodAction extends Actions {
    protected $woodServices;
    
    public function __construct(WoodServices $woodServices, LoggerInterface $logger) {
        parent::__construct($logger);
        $this->woodServices = $woodServices;
    }
}