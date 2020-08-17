<?php

namespace App\Domain\Bo;

use App\Domain\Bo\BoRepository;

class BoServices {
    private $repository;

    public function __construct(BoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int id La id cua Nhom, Neu khong co se fetch tat ca
     */
    public function listBo($nhomId) {
        $boList = $this->repository->listBo($nhomId);
        return $boList;
    }
}