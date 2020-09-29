<?php

namespace App\Domain\Category;
use App\Domain\Category\CategogyRepository;

class CategoryServices {
    private $repository;

    public function __construct(CategogyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetchCategory() {
        $category = $this->repository->fetchCategory();
        return $category;
    }
}