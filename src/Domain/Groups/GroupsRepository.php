<?php

namespace App\Domain\Groups;

use PDO;

class GroupsRepository {
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

    /**
     * @return array Groups The name of Groups list
     */
    public function fetchGroup() {
        $sql = 'select * FROM vncreatu_vncreature_new.group order by name_vn asc;';
        $db = $this->connection->prepare($sql);
        $db->execute();
        $Groups = $db->fetchAll();
        return $Groups;
    }

    /**
     * @param int loaiId The loaiId of Species
     * @return array Groups The name of Groups list
     */
    public function GroupsListBySpecies($loaiId) {
        $sql = 'SELECT * FROM group where spec=:loaiId';
        $db = $this->connection->prepare($sql);
        $db->execute(['loaiId' => $loaiId]);
        $Groups = $db->fetchAll();
        return $Groups;
    }
}