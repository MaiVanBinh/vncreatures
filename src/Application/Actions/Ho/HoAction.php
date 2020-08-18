<?php

namespace App\Application\Actions\Ho;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Actions;
use App\Domain\Ho\HoServices;

abstract class HoAction extends Actions{
    protected $services;

    public function __construct(LoggerInterface $logger, HoServices $services)
    {
        parent::__construct($logger);
        $this->services = $services;
    }
}