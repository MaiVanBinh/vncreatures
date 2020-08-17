<?php
namespace App\Domain\Classes;

use App\Domain\Classes\ClassesRepository;

class ClassesServices {
    /**
     * @ClassesRepository
     */
    private $repository;

    /**
     * Constructer
     * 
     * @param ClassesRepository
     * @return Void
     */
    public function __construct(ClassesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getClassesBySpecies($loaiId) {
        $classes = $this->repository->classesListBySpecies($loaiId);
        return $classes;
    }
}