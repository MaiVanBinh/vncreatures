<?php

namespace App\Domain\LatinDic;
use App\Domain\LatinDic\LatinDicRepository;


class LatinDicServices {
    private $repository;
    public function __construct(LatinDicRepository $repository)
    {
        $this->repository = $repository;
    }

    public function fetchLatinDic($latin) {
        $viet = $this->repository->fetchLatinDic($latin);
        return $viet;
    }
}