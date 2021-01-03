<?php

namespace App\Domain\Orders;

use Exception;
use PDO;

class OrdersServices {
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    /**
     * @param int id La id cua Nhom, Neu khong co se fetch tat ca
     */
    public function fetchOrder($entires = null, $page = 1, $isFilter = false, $name_vn = null) {
        if($isFilter) {
            $sql = "SELECT id, name_vn, orders.group FROM vncreatu_vncreature_new.orders order by name_vn asc";
            $db = $this->connection->prepare($sql);
            $db->execute();
            $order = $db->fetchAll();
            return $order;
        }
        $sql = "SELECT * FROM vncreatu_vncreature_new.orders order by name_vn asc";
        $sqlCount = "SELECT count(id) as total FROM vncreatu_vncreature_new.orders";
        if($entires) {
            $offset = ($page - 1) * $entires;
            $sql = "SELECT * FROM vncreatu_vncreature_new.orders order by name_vn asc limit {$entires} offset {$offset};";
            if($name_vn) {
                $sql = "SELECT * FROM vncreatu_vncreature_new.orders where name_vn like '%{$name_vn}%' order by name_vn asc limit {$entires} offset {$offset};";
                $sqlCount = "SELECT count(id) as total FROM vncreatu_vncreature_new.orders where name_vn like '%{$name_vn}%';";
            }
        }
        $db = $this->connection->prepare($sqlCount);
        $db->execute();
        $total = $db->fetchAll();
        $db = $this->connection->prepare($sql);
        $db->execute();
        $order = $db->fetchAll();
        return ['total' => $total[0]['total'], 'orders' => $order];
    }

    public function countByGroup($groupId) {
        $sql = "SELECT COUNT(id) AS total FROM vncreatu_vncreature_new.orders where orders.group={$groupId}";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $result = $db->fetchAll();
        $total = $result[0]['total'];
        return $total;
    }

    public function create($name_vn, $name_latin, $group, $userId) {
        try {
            $sql = "INSERT INTO vncreatu_vncreature_new.orders (name_vn, name_latin, orders.group, created_by, updated_by) values (:name_vn, :name_latin, :group, :userId, :userId)";
            $db = $this->connection->prepare($sql);
            $db->bindParam(':name_vn', $name_vn, PDO::PARAM_STR);
            $db->bindParam(':name_latin', $name_latin, PDO::PARAM_STR);
            $db->bindParam(':group', $group, PDO::PARAM_INT);
            $db->bindParam(':userId', $userId, PDO::PARAM_INT);
            $db->bindParam(':userId', $userId, PDO::PARAM_INT);
            $db->execute();
            return (int)$this->connection->lastInsertId();
        } catch (Exception $ex) {
            throw $ex->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM vncreatu_vncreature_new.orders WHERE id=:id";
            $db = $this->connection->prepare($sql);
            $db->bindParam(':id', $id, PDO::PARAM_INT);
            $db->execute();
            // return $sql;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function fetchOrderById($id) {
        try {
            $sql = "SELECT * from vncreatu_vncreature_new.orders where id=:id";
            $db = $this->connection->prepare($sql);
            $db->bindParam(':id', $id, PDO::PARAM_INT);
            $db->execute();
            $orders = $db->fetchAll();
            return $orders; 
        } catch (Exception $ex) {
            throw $ex->getMessage();
        }
    }

    public function update($id, $name_vn, $name_latin, $group, $userId) {
        try {
            $dateNow = date('Y-m-d H:i:s');
            $sql = "UPDATE vncreatu_vncreature_new.orders 
                    SET 
                        name_vn=:name_vn, 
                        name_latin=:name_latin,
                        orders.group=:group,
                        updated_by=:updated_by, 
                        updated_at=:updated_at
                        WHERE id=:id";
            
            $db = $this->connection->prepare($sql);
            $db->bindParam(':id', $id, PDO::PARAM_INT);
            $db->bindParam(':name_vn', $name_vn, PDO::PARAM_STR);
            $db->bindParam(':name_latin', $name_latin, PDO::PARAM_STR);
            $db->bindParam(':group', $group, PDO::PARAM_INT);
            $db->bindParam(':updated_by', $userId, PDO::PARAM_INT);
            $db->bindParam(':updated_at', $dateNow, PDO::PARAM_STR);
            $db->execute();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}