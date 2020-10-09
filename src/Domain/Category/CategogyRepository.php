<?php

namespace App\Domain\Category;
use PDO;

class CategogyRepository {
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function fetchCategory() {
        $sql = "SELECT id, name_vn from vncreatu_vncreature_new.posts_category WHERE name_vn='TỰ NHIÊN BÍ ẨN' OR name_vn='THÔNG TIN MỚI'";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $category = $db->fetchAll();
        return $category;
    }
}