<?php

namespace App\Domain\Family;

use PDO;

class FamilyRepository {
    /**
     * @var connection
     */
    private $connection;

    /**
     * Constructor
     * 
     * @param PDO connection The database connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param int id La id cua Ho, Neu khong co se fetch tat ca
     */
    public function fetchFamily() {
        $sql = 'SELECT * FROM family order by name_vn asc';
        $db = $this->connection->prepare($sql);
        $db->execute();
        $family = $db->fetchAll();
        return $family;
    }
}

