<?php

namespace App\Application\Actions\Author;
use App\Application\Actions\Actions;
use Psr\Log\LoggerInterface;
use App\Domain\Author\AuthorServices;

abstract class AuthorAction extends Actions{
    protected $aServices;
    public function __construct(LoggerInterface $logger, AuthorServices $aServices)
    {
        parent::__construct($logger);
        $this->aServices = $aServices;
    }
}
