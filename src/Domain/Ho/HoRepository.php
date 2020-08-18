<?php

namespace App\Domain\Ho;

use PDO;

class HoRepository {
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
    public function listHo($boId = null) {
        $sql = !$boId ? 'SELECT * FROM ho' : 'SELECT * FROM ho WHERE Bo=:boId';
        $db = $this->connection->prepare($sql);
        $db->execute(['boId' => $boId]);
        $ho = $db->fetchAll();
        return $ho;
    }
}

