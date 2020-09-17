<?php

namespace App\Domain\Order;
use PDO;

class OrderRepository {
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    /**
     * @param int id La id cua Nhom, Neu khong co se fetch tat ca
     */
    public function fetchOrder() {
        $sql = 'SELECT * FROM vncreatures.orders order by name_vn asc';
        $db = $this->connection->prepare($sql);
        $db->execute();
        $order = $db->fetchAll();
        return $order;
    }
}