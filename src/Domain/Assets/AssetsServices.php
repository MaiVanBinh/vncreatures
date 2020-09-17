<?php
namespace App\Domain\Assets;
use App\Domain\Assets\AssetsRepository;

class AssetsServices {
    private $repository;
    public function __construct(AssetsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetchCreatureImage($creatureId) {
        $images = $this->repository->fetchCreatureImage($creatureId);
        return $images;
    }
}