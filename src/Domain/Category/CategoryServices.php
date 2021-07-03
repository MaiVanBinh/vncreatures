<?php

namespace App\Domain\Category;

use PDO;

class CategoryServices
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

     public function create($name_vn, $name_en, $list, $userId)
    {
        $sql = "INSERT INTO posts_category (name_vn, name_en, list, created_by, updated_by) values (:name_vn, :name_en, :list, :userId, :userId);";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':name_vn', $name_vn, PDO::PARAM_STR);
        $db->bindParam(':name_en', $name_en, PDO::PARAM_STR);
        $db->bindParam(':userId', $userId, PDO::PARAM_INT);
        $db->bindParam(':list', $list, PDO::PARAM_INT);
        $db->execute();
        return ['id' => (int)$this->connection->lastInsertId()];
    }

    public function fetchCategory($name_vn = '', $from = null, $to = null, $page = 1, $limit = 1, $isAll = false)
    {
        $sql = '';
        $sqlCount = '';
        $total = 0;
        // fetch all category
        if($isAll) {
            $sqlCount = 'SELECT count(id) as total from posts_category;';
            $dbCount = $this->connection->prepare($sqlCount);

            $sql = 'SELECT * from posts_category;';
            $db = $this->connection->prepare($sql);
        } else { // Fetch Category by page
            $offset = ($page - 1) * $limit;
        
            if (!$name_vn) {
                // sql for count
                $sqlCount = 'SELECT count(id) as total from posts_category;';
                $dbCount = $this->connection->prepare($sqlCount);
    
                // sql for select
                $sql = 'SELECT * from posts_category limit :limit offset :offset';
                $db = $this->connection->prepare($sql);
            }
            if ($name_vn) {
                $sqlCount = 'SELECT count(id) as total from posts_category where name_vn like :name_vn;';
                $dbCount = $this->connection->prepare($sqlCount);
                $dbCount->bindParam(':name_vn', $keyword, PDO::PARAM_STR);
    
                $sql = 'SELECT * from posts_category where name_vn like :name_vn limit :limit offset :offset;';
                $db = $this->connection->prepare($sql);
                $keyword = "%" . $name_vn . "%";
                $db->bindParam(':name_vn', $keyword, PDO::PARAM_STR);
            }
            if ($from && $to) {
                $sqlCount = 'SELECT count(id) as total from posts_category where name_vn like :name_vn and created_at between :from and :to;';
                $dbCount = $this->connection->prepare($sqlCount);
                $dbCount->bindParam(':name_vn', $keyword, PDO::PARAM_STR);
                $dbCount->bindParam(':from', $from, PDO::PARAM_STR);
                $dbCount->bindParam(':to', $to, PDO::PARAM_STR);
    
                $sql = 'SELECT * from posts_category where name_vn like :name_vn and created_at between :from and :to limit :limit offset :offset;';
                $db = $this->connection->prepare($sql);
                $keyword = "%" . $name_vn . "%";
                $db->bindParam(':name_vn', $keyword, PDO::PARAM_STR);
                $db->bindParam(':from', $from, PDO::PARAM_STR);
                $db->bindParam(':to', $to, PDO::PARAM_STR);
            }
            $db->bindParam(':limit', $limit, PDO::PARAM_INT);
            $db->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        
        $dbCount->execute();
        $total = (int) $dbCount->fetchAll()[0]['total'];

        $db->execute();
        $category = $db->fetchAll();
        return ['category' => $category, 'total' => $total];
    }

    public function update($categoryId, $name_vn, $name_en, $list, $userId) {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date("Y-m-d H:i:s");
        $sql = "UPDATE posts_category set list=:list, name_vn=:name_vn, name_en=:name_en, updated_by=:userId, updated_at=:date where id=:categoryId";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':name_vn', $name_vn, PDO::PARAM_STR);
        $db->bindParam(':name_en', $name_en, PDO::PARAM_STR);
        $db->bindParam(':userId', $userId, PDO::PARAM_INT);
        $db->bindParam(':list', $list, PDO::PARAM_INT);
        $db->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $db->bindParam(':date', $date, PDO::PARAM_STR);
        $db->execute();
    }

    // fetch category by id, return category if exist or false if not
    public function fetchCategoryById($id) {

        $sql = "SELECT * FROM posts_category where id=:id;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
        $posts_category = $db->fetchAll();
        return $posts_category;
    }

    // delete 
    public function delete($id) {
        $sql = "DELETE FROM posts_category WHERE id=:id";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
    }
}