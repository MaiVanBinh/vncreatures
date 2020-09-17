<?php
namespace App\Domain\Assets;
use PDO;

class AssetsRepository {
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    public function fetchCreatureImage($creatureId) {
        $sql = "SELECT 
            a.id, a.url 
        FROM vncreatures.assets a, 
            (SELECT asset FROM vncreatures.assets_creatures where creature=:creatureId) ac 
        where a.id = ac.asset;";
        $db = $this->connection->prepare($sql);
        $db->execute(['creatureId' => $creatureId]);
        $images = $db->fetchAll();
        return $images;
    }
}