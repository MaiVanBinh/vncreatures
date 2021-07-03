<?php

namespace App\Domain\Footprint;

use Exception;
use PDO;

class FootprintServices
{
    /**
     * @var PDO
     */
    private $connection;

    /**
     * Constructor
     * 
     * @param PDO connection
     * @return Void
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function fetchFootprint($limit = 10, $page = 1, $name="") {
        $offset = ($page-1)*$limit;
        $nameString = '%' . $name . '%';
        $sql = "SELECT f.*, f.avatar as avatarId, a.url as avatar from footprint f, assets a where f.avatar=a.id and f.name_vn like :nameString limit :limit offset :offset";
        $sqlCount = "SELECT count(id) as total from footprint where name_vn like :nameString;";
        $db = $this->connection->prepare($sql);
        $dbCount = $this->connection->prepare($sqlCount);
        $db->bindParam(':limit',$limit, PDO::PARAM_INT);
        $db->bindParam(':offset',$offset, PDO::PARAM_INT);
        $db->bindParam(':nameString',$nameString, PDO::PARAM_STR);
        $dbCount->bindParam(':nameString',$nameString, PDO::PARAM_STR);
        $db->execute();
        $footprint = $db->fetchAll();
        $dbCount->execute();
        $total = $dbCount->fetchAll()[0]['total'];
        return ['total' => $total, 'footprint' => $footprint];
    }
    
    public function createFootprint($name_vn, $name_latin, $name_en, $avatar, $creature = null,  $user_id) {
        $sql = "INSERT INTO footprint (name_vn, name_latin, name_en, avatar, creatures, created_by, updated_by) values (:name_vn, :name_latin, :name_en, :avatar, :creature, :userId, :userId);";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':name_vn', $name_vn, PDO::PARAM_STR);
        $db->bindParam(':name_latin', $name_latin, PDO::PARAM_STR);
        $db->bindParam(':name_en', $name_en, PDO::PARAM_STR);
        $db->bindParam(':avatar', $avatar, PDO::PARAM_INT);
        $db->bindParam(':creature', $creature, PDO::PARAM_INT);
        $db->bindParam(':userId', $user_id, PDO::PARAM_INT);
        $db->execute();
    }
    public function delete($id) {
        $sql = "DELETE FROM footprint WHERE id=:id";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
    }
    public function update($id, $name_vn, $name_latin, $name_en, $avatar, $creature = null,  $user_id) {
        $date = date('Y-m-d H:i:s');
        $sql = "UPDATE footprint SET name_vn=:name_vn, name_latin=:name_latin, name_en=:name_en, creatures=:creature, avatar=:avatar, updated_by=:userId, updated_at=:dateU WHERE id=:id;";
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