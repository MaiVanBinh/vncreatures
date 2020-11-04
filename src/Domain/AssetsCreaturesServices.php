<?php

namespace App\Domain;

use Exception;
use PDO;

class AssetsCreaturesServices {
    private $connection;

    public function __construct(PDO $connection) 
    {
        $this->connection = $connection;
    }

    public function deletedImages($images, $creatureId) {
        try {
            if(true) {
                $sql = "UPDATE assets_creatures SET deleted=1 WHERE asset in ($images);";
                $this->connection->prepare($sql)->execute();
            }
        } catch(Exception $ex) {
            throw $ex;
        }
    }

    public function createNewOne($assetId, $creatureId) {
        try {
            $sql = "INSERT INTO assets_creatures (asset, creature) VALUES ({$assetId}, {$creatureId});";
            $db = $this->connection->prepare($sql);
            $db->execute();
        } catch(Exception $ex) {
            throw $ex;
        }
    }
}