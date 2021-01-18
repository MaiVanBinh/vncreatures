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

    public function fetchAsset($page = 1)
    {
        $offset = 15 * ($page - 1);
        $sql = "SELECT * FROM vncreatu_vncreature_new.assets limit 15 OFFSET {$offset};";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $assets = $db->fetchAll();
        return $assets;
    }

    public function fetchCreatureImage($creatureId)
    {
        $sql = "SELECT 
            a.id, a.url 
        FROM vncreatu_vncreature_new.assets a, 
            (SELECT asset FROM vncreatu_vncreature_new.assets_creatures where creature=:creatureId and deleted=0) ac 
        where a.id = ac.asset;";
        $db = $this->connection->prepare($sql);
        $db->execute(['creatureId' => $creatureId]);
        $images = $db->fetchAll();
        return $images;
    }

    public function createAsset($url, $name)
    {
        $sql = "INSERT INTO vncreatu_vncreature_new.assets (url, name) VALUES (:url, :name);";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':url', $url, PDO::PARAM_STR);
        $db->bindParam(':name', $name, PDO::PARAM_STR);
        $db->execute();
        return (int)$this->connection->lastInsertId();
    }

    public function countEntries()
    {
        $sql = "SELECT count(id) as total from vncreatu_vncreature_new.assets;";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $total = $db->fetchAll();
        $total = $total[0]['total'];
        return $total;
    }

    public function fetchAssetById($id)
    {
        $sql = "SELECT * from vncreatu_vncreature_new.assets where id=:id;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
        $assets = $db->fetchAll();
        return $assets[0];
    }

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
}
