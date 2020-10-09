<?php

namespace App\Application\Actions\NationalParks;
use App\Domain\NationalParks\NationalParksServecies;
use App\Application\Actions\Actions;
use Psr\Log\LoggerInterface;
abstract class NationalParksActions extends Actions {
    protected $npServices;

    public function __construct(NationalParksServecies $services, LoggerInterface $logger) 
    {
        parent::__construct($logger);
        $this->npServices = $services;
    }
}