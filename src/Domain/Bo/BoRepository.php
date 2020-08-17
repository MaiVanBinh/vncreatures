<?php

namespace App\Domain\Bo;
use PDO;

class BoRepository {
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    /**
     * @param int id La id cua Nhom, Neu khong co se fetch tat ca
     */
    public function listBo($nhomId = null) {
        $sql = !$nhomId ? 'SELECT * FROM bo' : 'SELECT * FROM bo WHERE Nhom=:nhomId';
        $db = $this->connection->prepare($sql);
        $db->execute(['nhomId' => $nhomId]);
        $bo = $db->fetchAll();
        return $bo;
    }
}