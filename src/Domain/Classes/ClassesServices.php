<?php
namespace App\Domain\Classes;

use App\Domain\Classes\ClassesRepository;
use SebastianBergmann\Timer\Duration;

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

    public function getClassesBySpecies($id) {
        $classes = $this->repository->classesListBySpecies($id);
        return $classes;
    }
}