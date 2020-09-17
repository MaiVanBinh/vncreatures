<?php

namespace App\Application\Actions\Order;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Actions;
use App\Domain\Order\OrderServices;

abstract class OrderActions extends Actions{
    protected $services;

    public function __construct(LoggerInterface $logger, OrderServices $services)
    {
        parent::__construct($logger);
        $this->services = $services;    
    }
}