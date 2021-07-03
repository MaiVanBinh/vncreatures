<?php

namespace App\Domain;
use PDO;

class AssetsPostServices {
    private $connection;

    public function __construct(PDO $connection)
    {   
        $this->connection = $connection;
    }

    public function checkLinkExist($postId, $imageId) {
        $sql = 'SELECT * FROM assets_posts where post=:postId and asset=:imageId;';
        $db = $this->connection->prepare($sql);
        $db->bindParam(':postId', $postId, PDO::PARAM_INT);
        $db->bindParam(':imageId', $imageId, PDO::PARAM_INT);
        $db->execute();
        $images = $db->fetchAll();
        if(count($images) > 0) {
            return true;
        }
        return false;
    }

    public function linkPostWithImage($postId, $imageId, $userId) {
        // check link exist
        if(!$this->checkLinkExist($postId, $imageId)) {
            $sql = "INSERT INTO assets_posts (post, asset, created_by, updated_by) value (:postId, :imageId, :userId, :userId);";
            $db = $this->connection->prepare($sql);
            $db->bindParam(':postId', $postId, PDO::PARAM_INT);
            $db->bindParam(':imageId', $imageId, PDO::PARAM_INT);
            $db->bindParam(':userId', $userId, PDO::PARAM_INT);
            $db->execute();
        }
    }
}