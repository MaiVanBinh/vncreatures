<?php

namespace App\Domain\Wood;

use PDO;

class WoodServices {
    private $connection;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }
    
    public function fetchWoodprintById($id) {
        $sql = 'Select * from wood_form where id =:id';
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id',$id, PDO::PARAM_INT);
        $db->execute();
        $footprint = $db->fetchAll();
        return $footprint;
    }

    public function fetchWoodForm($limit = 40, $page = 1, $name="") {
        $offset = ($page-1)*$limit;
        $nameString = '%' . $name . '%';
        $sql = "SELECT f.*, f.img as imgId, a.url as img from wood_form f, assets a where f.img=a.id and f.name_vn like :nameString order by f.created_at desc limit :limit offset :offset";
        $sqlCount = "SELECT count(id) as total from wood_form where name_vn like :nameString;";
        $db = $this->connection->prepare($sql);
        $dbCount = $this->connection->prepare($sqlCount);
        $db->bindParam(':limit',$limit, PDO::PARAM_INT);
        $db->bindParam(':offset',$offset, PDO::PARAM_INT);
        $db->bindParam(':nameString',$nameString, PDO::PARAM_STR);
        $dbCount->bindParam(':nameString',$nameString, PDO::PARAM_STR);
        $db->execute();
        $wood_form = $db->fetchAll();
        $dbCount->execute();
        $total = $dbCount->fetchAll()[0]['total'];
        return ['total' => $total, 'woods' => $wood_form];
    }
        
    public function create($name_vn, $name_latin, $name_en, $img, $creature = null,  $user_id) {
        $sql = "INSERT INTO wood_form (name_vn, name_latin, name_en, img, creature, created_by, updated_by) values (:name_vn, :name_latin, :name_en, :avatar, :creature, :userId, :userId);";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':name_vn', $name_vn, PDO::PARAM_STR);
        $db->bindParam(':name_latin', $name_latin, PDO::PARAM_STR);
        $db->bindParam(':name_en', $name_en, PDO::PARAM_STR);
        $db->bindParam(':avatar', $img, PDO::PARAM_INT);
        $db->bindParam(':creature', $creature, PDO::PARAM_INT);
        $db->bindParam(':userId', $user_id, PDO::PARAM_INT);
        $db->execute();
    }
    
    
    public function delete($id) {
        $sql = "DELETE FROM wood_form WHERE id=:id";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
    }
    
    
    public function update($id, $name_vn, $name_latin, $name_en, $avatar, $creature = null,  $user_id) {
        $date = date('Y-m-d H:i:s');
        $sql = "UPDATE wood_form SET name_vn=:name_vn, name_latin=:name_latin, name_en=:name_en, creature=:creature, img=:avatar, updated_by=:userId, updated_at=:dateU WHERE id=:id;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':name_vn', $name_vn, PDO::PARAM_STR);
        $db->bindParam(':name_latin', $name_latin, PDO::PARAM_STR);
        $db->bindParam(':name_en', $name_en, PDO::PARAM_STR);
        $db->bindParam(':avatar', $avatar, PDO::PARAM_INT);
        $db->bindParam(':creature', $creature, PDO::PARAM_INT);
        $db->bindParam(':userId', $user_id, PDO::PARAM_INT);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->bindParam(':dateU', $date, PDO::PARAM_STR);
        $db->execute();
    }
}


