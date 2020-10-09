<?php

namespace App\Domain\NationalParks;
use App\Domain\NationalParks\NationalParksRepository;

class NationalParksServecies {
    private $repository;

    public function __construct(NationalParksRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetchNationalParkById($id) {
        $nationalPark = $this->repository->fetchNationalParkById($id);
        return $nationalPark;
    }

    public function fetchNationParks() {
        $npCoords = $this->repository->fetchNationParks();
        return $npCoords;
    }
}