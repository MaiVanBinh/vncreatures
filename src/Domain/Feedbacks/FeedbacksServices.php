<?php

namespace App\Domain\Feedbacks;

use PDO;
use Exception;

class FeedbacksServices
{
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

    // public function fetchFamilies($limit = 10, $page = 1, $name = '', $order = null)
    // {
    //     $sql = 'SELECT f.*, o.name_vn as order_vn FROM families f, vncreatu_vncreatures.orders o where o.id=f.order and f.name_vn like :nameString limit :limit offset :offset;';
    //     $sqlCount = 'SELECT count(id) as total FROM families where name_vn like :nameString';
    //     $db = $this->connection->prepare($sql);
    //     $dbCount = $this->connection->prepare($sqlCount);

    //     if ($order) {
    //         $sql = 'SELECT f.*, o.name_vn as order_vn FROM families f, vncreatu_vncreatures.orders o where o.id=f.order and f.name_vn like :nameString and f.order=:order limit :limit offset :offset;';
    //         $sqlCount = 'SELECT count(id) as total FROM families where name_vn like :nameString and families.order=:order';
    //         $db = $this->connection->prepare($sql);
    //         $dbCount = $this->connection->prepare($sqlCount);
    //         $db->bindParam(':order', $order, PDO::PARAM_INT);
    //         $dbCount->bindParam(':order', $order, PDO::PARAM_INT);
    //     }
    //     $offset = ($page - 1) * $limit;
    //     $nameString = '%' . $name . '%';
    //     $db->bindParam(':nameString', $nameString, PDO::PARAM_STR);
    //     $db->bindParam(':limit', $limit, PDO::PARAM_INT);
    //     $db->bindParam(':offset', $offset, PDO::PARAM_INT);
    //     $dbCount->bindParam(':nameString', $nameString, PDO::PARAM_STR);

    //     $dbCount->execute();
    //     $total = $dbCount->fetchAll();
    //     $db->execute();
    //     $families = $db->fetchAll();
    //     return ['total' => $total[0]['total'], 'families' => $families];
    // }

    // public function countByOrder($orderId) {
    //     $sql = "SELECT COUNT(id) AS total FROM families where families.order=:orderId";
    //     $db = $this->connection->prepare($sql);
    //     $db->bindParam(':orderId', $orderId, PDO::PARAM_INT);
    //     $db->execute();
    //     $result = $db->fetchAll();
    //     $total = $result[0]['total'];
    //     return $total;
    // }

    public function create($email, $message, $star)
    {
        try {
            $sql = "INSERT INTO feedbacks (email, message, star) values (:email, :message, :star)";
            $db = $this->connection->prepare($sql);
            $db->bindParam(':email', $email, PDO::PARAM_STR);
            $db->bindParam(':star', $star, PDO::PARAM_INT);
            $db->bindParam(':message', $message, PDO::PARAM_STR);
            $db->execute();
            return (int)$this->connection->lastInsertId();
        } catch (Exception $ex) {
            throw $ex->getMessage();
        }
    }
    
    public function fetchFeebacks($limit = 10, $page = 1, $name = '', $order = null)
    {
        $sql = 'SELECT * FROM feedbacks limit :limit offset :offset;';
        $sqlCount = 'SELECT count(id) as total FROM feedbacks;';
        $db = $this->connection->prepare($sql);
        $dbCount = $this->connection->prepare($sqlCount);

        $offset = ($page - 1) * $limit;
        $db->bindParam(':limit', $limit, PDO::PARAM_INT);
        $db->bindParam(':offset', $offset, PDO::PARAM_INT);

        $dbCount->execute();
        $total = $dbCount->fetchAll();
        $db->execute();
        $feedbacks = $db->fetchAll();
        return ['total' => $total[0]['total'], 'feedbacks' => $feedbacks];
    }
    

    // public function fetchFamilyById($id) {
    //     try {
    //         $sql = "SELECT * from families where id=:id";
    //         $db = $this->connection->prepare($sql);
    //         $db->bindParam(':id', $id, PDO::PARAM_INT);
    //         $db->execute();
    //         $families = $db->fetchAll();
    //         return $families;
    //     } catch (Exception $ex) {
    //         throw $ex->getMessage();
    //     }
    // }

    // public function delete($id)
    // {
    //     try {
    //         $sql = "DELETE FROM families WHERE id=:id";
    //         $db = $this->connection->prepare($sql);
    //         $db->bindParam(':id', $id, PDO::PARAM_INT);
    //         $db->execute();
    //         // return $sql;
    //     } catch (Exception $ex) {
    //         throw $ex;
    //     }
    // }
    // public function update($id, $name_vn, $name_latin, $order, $userId) {
    //     try {
    //         $dateNow = date('Y-m-d H:i:s');
    //         $sql = "UPDATE families 
    //                 SET 
    //                     name_vn=:name_vn, 
    //                     name_latin=:name_latin,
    //                     families.order=:order,
    //                     updated_by=:updated_by, 
    //                     updated_at=:updated_at
    //                     WHERE id=:id";

    //         $db = $this->connection->prepare($sql);
    //         $db->bindParam(':id', $id, PDO::PARAM_INT);
    //         $db->bindParam(':name_vn', $name_vn, PDO::PARAM_STR);
    //         $db->bindParam(':name_latin', $name_latin, PDO::PARAM_STR);
    //         $db->bindParam(':order', $order, PDO::PARAM_INT);
    //         $db->bindParam(':updated_by', $userId, PDO::PARAM_INT);
    //         $db->bindParam(':updated_at', $dateNow, PDO::PARAM_STR);
    //         $db->execute();
    //     } catch (Exception $ex) {
    //         throw $ex;
    //     }
    // }
    // public function countFamilies() {
    //     $sql = "SELECT COUNT(id) AS total FROM families;";
    //     $db = $this->connection->prepare($sql);
    //     $db->execute();
    //     $result = $db->fetchAll();
    //     $total = $result[0]['total'];
    //     return $total;
    // }
}
