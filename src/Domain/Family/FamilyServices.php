<?php

namespace App\Domain\Family;

use App\Domain\Family\FamilyRepository;

class FamilyServices {
    private $repository;

    public function __construct(FamilyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int id La id cua Bo, Neu khong co se fetch tat ca
     */
    public function fetchFamily() {
        $familyList = $this->repository->fetchFamily();
        return $familyList;
    }
}