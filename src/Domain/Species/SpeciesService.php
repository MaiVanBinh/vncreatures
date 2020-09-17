<?php

namespace App\Domain\Species;

use App\Domain\Species\SpeciesRepository;

class SpeciesService {
    private $repository;

    public function __construct(SpeciesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * fetch Species services
     * @return Array Species
     */
    public function fetchSpecies() {
        $species = $this->repository->fetchSpecies();
        return $species;
    }
}