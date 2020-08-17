<?php

namespace App\Application\Actions\Species;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Actions;
use App\Domain\Species\SpeciesService;

abstract class SpeciesAction extends Actions{
    /**
     * @var SpeciesService
     */
    protected $speciesService;

    /**
     * Construct
     * @param LoggerInterface
     * @param SpeciesService
     * 
     * @return Void
     */
    public function __construct(LoggerInterface $logger, SpeciesService $speciesService)
    {
        parent::__construct($logger);
        $this->speciesService = $speciesService;
    }
}