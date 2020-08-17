<?php

namespace App\Application\Actions\Classes;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Actions;
use App\Domain\Classes\ClassesServices;

abstract class ClassesAction extends Actions{
    protected $services;

    public function __construct(LoggerInterface $logger, ClassesServices $services)
    {
        parent::__construct($logger);
        $this->services = $services;
    }
}