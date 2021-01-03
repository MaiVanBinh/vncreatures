<?php

namespace App\Domain\Families;
use PDO;
use Exception;

class FamiliesServices {
   /**
     * @var connection
     */
    private $connection;

    /**
     * Constructor
     * 
     * @param PDO connection The database connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int id La id cua Ho, Neu khong co se fetch tat ca
     */
    public function fetchFamilies($entires = null, $page = 1, $isFilter = false, $name_vn = null) {
        if($isFilter) {
            $sql = 'SELECT id, name_vn, families.order FROM families order by name_vn asc';
            $db = $this->connection->prepare($sql);
            $db->execute();
            $families = $db->fetchAll();
            return $families;
        }

        $sql = 'SELECT * FROM families order by name_vn asc';
        $sqlCount = 'SELECT count(id) as total FROM families';
        if($entires) {
            $offset = ($page - 1) * $entires;
            $sql = "SELECT * FROM families order by name_vn asc limit {$entires} offset {$offset};";
            if($name_vn) {
                $sql = "SELECT * FROM families where name_vn like '%{$name_vn}%' order by name_vn asc limit {$entires} offset {$offset};";
                $sqlCount = "SELECT count(id) as total FROM families where name_vn like '%{$name_vn}%';";
            }
        }
        $db = $this->connection->prepare($sqlCount);
        $db->execute();
        $total = $db->fetchAll();
        $db = $this->connection->prepare($sql);
        $db->execute();
        $families = $db->fetchAll();
        return ['total' => $total[0]['total'], 'families' => $families];
    }

    public function countByOrder($orderId) {
        $sql = "SELECT COUNT(id) AS total FROM vncreatu_vncreature_new.families where families.order=:orderId";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $db->execute();
        $result = $db->fetchAll();
        $total = $result[0]['total'];
        return $total;
    }
    
    public function create($name_vn, $name_latin, $order, $userId) {
        try {
            $sql = "INSERT INTO vncreatu_vncreature_new.families (name_vn, name_latin, families.order, created_by, updated_by) values (:name_vn, :name_latin, :order, :userId, :userId)";
            $db = $this->connection->prepare($sql);
            $db->bindParam(':name_vn', $name_vn, PDO::PARAM_STR);
            $db->bindParam(':name_latin', $name_latin, PDO::PARAM_STR);
            $db->bindParam(':order', $order, PDO::PARAM_INT);
            $db->bindParam(':userId', $userId, PDO::PARAM_INT);
            $db->bindParam(':userId', $userId, PDO::PARAM_INT);
            $db->execute();
            return (int)$this->connection->lastInsertId();
        } catch (Exception $ex) {
            throw $ex->getMessage();
        }
    }

    public function fetchFamilyById($id) {
        try {
            $sql = "SELECT * from vncreatu_vncreature_new.families where id=:id";
            $db = $this->connection->prepare($sql);
            $db->bindParam(':id', $id, PDO::PARAM_INT);
            $db->execute();
            $families = $db->fetchAll();
            return $families;
        } catch (Exception $ex) {
            throw $ex->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM vncreatu_vncreature_new.families WHERE id=:id";
            $db = $this->connection->prepare($sql);
            $db->bindParam(':id', $id, PDO::PARAM_INT);
            $db->execute();
            // return $sql;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    public function update($id, $name_vn, $name_latin, $order, $userId) {
        try {
            $dateNow = date('Y-m-d H:i:s');
            $sql = "UPDATE vncreatu_vncreature_new.families 
                    SET 
                        name_vn=:name_vn, 
                        name_latin=:name_latin,
                        families.order=:order,
                        updated_by=:updated_by, 
                        updated_at=:updated_at
                        WHERE id=:id";
            
            $db = $this->connection->prepare($sql);
            $db->bindParam(':id', $id, PDO::PARAM_INT);
            $db->bindParam(':name_vn', $name_vn, PDO::PARAM_STR);
            $db->bindParam(':name_latin', $name_latin, PDO::PARAM_STR);
            $db->bindParam(':order', $order, PDO::PARAM_INT);
            $db->bindParam(':updated_by', $userId, PDO::PARAM_INT);
            $db->bindParam(':updated_at', $dateNow, PDO::PARAM_STR);
            $db->execute();
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}