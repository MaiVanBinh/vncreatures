<?php

namespace App\Domain\Author;
use App\Domain\Author\AuthorRepository;

class AuthorServices {
    private $repository;

    public function __construct(AuthorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetchAuthors($page) {
        $author = $this->repository->fecthAuthors($page);
        return $author;
    }
}