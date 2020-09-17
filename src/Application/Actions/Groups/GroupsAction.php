<?php

namespace App\Application\Actions\Groups;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Actions;
use App\Domain\Groups\GroupsServices;

abstract class GroupsAction extends Actions{
    protected $services;

    public function __construct(LoggerInterface $logger, GroupsServices $services)
    {
        parent::__construct($logger);
        $this->services = $services;
    }
}