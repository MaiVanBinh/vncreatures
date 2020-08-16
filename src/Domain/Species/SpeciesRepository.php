<?php

namespace App\Domain\Species;

use Exception;
use PDO;

class SpeciesRepository {
    private $connection;

    /**
     * @var PDO The database connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * List all species
     * 
     * 
     */
    public function listSpecies(){
        try {
            $sql = 'SELECT * FROM loai';
            $db = $this->connection->prepare($sql);
            $db->execute();
            $loai = $db->fetchAll();
            return $loai;
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}