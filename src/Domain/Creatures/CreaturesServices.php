<?php

namespace App\Domain\Creatures;

use App\Domain\Creatures\CreaturesRepository;

class CreaturesServices {

    private $repository;

    public function __construct(CreaturesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getCreaturesByFilter($filter) {
        $creatures = $this->repository->getCreaturesByFilter($filter);
        return $creatures;
    }
}

