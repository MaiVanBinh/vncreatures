<?php

namespace App\Domain\NationalParks;
use PDO;

class NationalParksRepository {
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function fetchNationalParkById($id) {
        $sql = "SELECT * FROM vncreatu_vncreature_new.national_parks WHERE id=:id;";
        $db = $this->connection->prepare($sql);
        $db->execute(['id' => $id]);
        $nationalPark = $db->fetchAll();
        return $nationalPark;
    }

    public function fetchNationParks() {
        $sql = "SELECT id, name, coords, location FROM vncreatu_vncreature_new.national_parks WHERE coords!='';";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $npCoords = $db->fetchAll();
        return $npCoords; 
    }
}