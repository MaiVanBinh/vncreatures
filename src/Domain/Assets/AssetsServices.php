<?php

namespace App\Domain\Assets;

use PDO;

class AssetsServices
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function fetchAsset($page = 1, $limit = 10, $name = '')
    {
        $offset = $limit * ($page - 1);
        
        $sql = "SELECT * FROM assets order by created_at DESC LIMIT :limit offset :offset ;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':offset', $offset, PDO::PARAM_INT);
        $db->bindParam(':limit', $limit, PDO::PARAM_INT);
        $nameString = '%' . $name . '%';
        // $db->bindParam(':nameString', $nameString, PDO::PARAM_STR);
        $db->execute();
        $images = $db->fetchAll();
        
        $sqlCount = "SELECT count(id) as total from assets;";
        $db = $this->connection->prepare($sqlCount);
        // $db->bindParam(':nameString', $nameString, PDO::PARAM_STR);
        $db->execute();
        $total = $db->fetchAll()[0]['total'];
        return ['total' => $total, 'images' => $images];
    }

    public function fetchAssetByFileName($fileName)
    {
        $sql = "SELECT * FROM assets where name like :nameString ;";
        $db = $this->connection->prepare($sql);
        $nameString = '%' . $fileName . '%';
        $db->bindParam(':nameString', $nameString, PDO::PARAM_STR);
        $db->execute();
        $images = $db->fetchAll();
        return $images;
    }

    public function fetchAssetById($id)
    {
        $sql = "SELECT * from assets where id=:id;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
        $assets = $db->fetchAll();
        return $assets;
    }
    public function fetchCreatureImage($creatureId)
    {
        $sql = "SELECT 
            a.id, a.url 
        FROM assets a, 
            (SELECT asset FROM assets_creatures where creature=:creatureId and deleted=0) ac 
        where a.id = ac.asset;";
        $db = $this->connection->prepare($sql);
        $db->execute(['creatureId' => $creatureId]);
        $images = $db->fetchAll();
        return $images;
    }

    public function createAsset($url, $name, $size, $userId)
    {
        $sql = "INSERT INTO assets (url, name, size, created_by, updated_by) VALUES (:url, :name, :size, :userId, :userId);";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':url', $url, PDO::PARAM_STR);
        $db->bindParam(':name', $name, PDO::PARAM_STR);
        $db->bindParam(':size', $size, PDO::PARAM_STR);
        $db->bindParam(':userId', $userId, PDO::PARAM_INT);
        $db->execute();
        return (int)$this->connection->lastInsertId();
    }

    public function countEntries()
    {
        $sql = "SELECT count(id) as total from assets;";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $total = $db->fetchAll();
        $total = $total[0]['total'];
        return $total;
    }

    // public function fetchAssetById($id)
    // {
    //     $sql = "SELECT * from assets where id=:id;";
    //     $db = $this->connection->prepare($sql);
    //     $db->bindParam(':id', $id, PDO::PARAM_INT);
    //     $db->execute();
    //     $assets = $db->fetchAll();
    //     return $assets[0];
    // }

    public function unLinkAssetCretures($creatureId)
    {
        $sql = "DELETE FROM assets_creatures where creature=:creatureId";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':creatureId', $creatureId, PDO::PARAM_INT);
        $db->execute();
    }
    public function unLinkAssetPost($postId)
    {
        $sql = "DELETE FROM assets_posts where post=:post";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':post', $postId, PDO::PARAM_INT);
        $db->execute();
    }

    public function unLink($asset) {
        $sql = "DELETE FROM assets_creatures where asset=:asset";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':asset', $asset, PDO::PARAM_INT);
        $db->execute();
        $sql = "DELETE FROM assets_posts where asset=:asset";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':asset', $asset, PDO::PARAM_INT);
        $db->execute();
    }

    public function deleteAsset($id) {
        $sql = "DELETE FROM assets WHERE id=:id;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
    }
    public function useImage($imageId, $isUse) {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date("Y-m-d H:i:s");
        $sql = 'UPDATE assets set in_use=:isUse, updated_at=:date where id=:imageId;';
        $db = $this->connection->prepare($sql);
        $db->bindParam(":isUse", $isUse, PDO::PARAM_BOOL);
        $db->bindParam(":imageId", $imageId, PDO::PARAM_INT);
        $db->bindParam(":date", $date, PDO::PARAM_STR);
        $db->execute();
    }
    public function fetchAssetByPostId($postId) {
        $sql = "SELECT a.id FROM assets a, assets_posts pa WHERE pa.post=:postId and a.id=pa.asset; ";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':postId', $postId, PDO::PARAM_INT);
        $db->execute();
        $images = $db->fetchAll();
        return $images;
    }
    public function unLinkImagePost($postId)
    {
        $sql = "DELETE FROM assets_posts where post=:post";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':post', $postId, PDO::PARAM_INT);
        $db->execute();
    }

    public function checkAssetInUse($imageId) {
        $sql = "SELECT * FROM assets_posts WHERE asset=:imageId;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':imageId', $imageId, PDO::PARAM_INT);
        $db->execute();
        $images = $db->fetchAll();
        if(count($images) > 0) {
            return true;
        }
        return false;
    }
    public function unlinkBaseOnImageAndPost($image, $post) {
        $sql = "DELETE FROM assets_posts where asset=:asset and post=:post";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':asset', $image, PDO::PARAM_INT);
        $db->bindParam(':post', $post, PDO::PARAM_INT);
        $db->execute();
    }
}