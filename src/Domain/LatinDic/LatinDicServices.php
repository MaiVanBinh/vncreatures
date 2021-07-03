<?php

namespace App\Domain\LatinDic;
use App\Domain\LatinDic\LatinDicRepository;


class LatinDicServices {
    private $repository;
    public function __construct(LatinDicRepository $repository)
    {
        $this->repository = $repository;
    }

    public function latinToViet($latin) {
        $viet = $this->repository->latinToViet($latin);
        return $viet;
    }
    public function VietToLatin($latin) {
        $viet = $this->repository->VietToLatin($latin);
        return $viet;
    }
}