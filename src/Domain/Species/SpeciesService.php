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
     * List Species services
     * @return Array Species
     */
    public function listSpecies() {
        $species = $this->repository->listSpecies();
        return $species;
    }
}