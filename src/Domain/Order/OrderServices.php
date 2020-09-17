<?php

namespace App\Domain\Order;

use App\Domain\Order\OrderRepository;

class OrderServices {
    private $repository;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int id La id cua Nhom, Neu khong co se fetch tat ca
     */
    public function fetchOrder() {
        $orderList = $this->repository->fetchOrder();
        return $orderList;
    }
}