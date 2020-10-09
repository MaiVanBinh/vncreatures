<?php

namespace App\Application\Actions\LatinDic;
use App\Application\Actions\Actions;
use App\Domain\LatinDic\LatinDicServices;
use Psr\Log\LoggerInterface;

abstract class LatinDicAction extends Actions {
    protected $services;

    public function __construct(LoggerInterface $logger, LatinDicServices $services)
    {
        parent::__construct($logger);
        $this->services = $services;
    }
}