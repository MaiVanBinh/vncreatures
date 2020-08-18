<?php

namespace App\Domain\Ho;

use App\Domain\Ho\HoRepository;

class HoServices {
    private $repository;

    public function __construct(HoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int id La id cua Bo, Neu khong co se fetch tat ca
     */
    public function listBo($boId) {
        $hoList = $this->repository->listHo($boId);
        return $hoList;
    }
}