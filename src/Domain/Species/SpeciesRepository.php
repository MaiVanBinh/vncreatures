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
     * @return Species list species
     */
    public function fetchSpecies(){
        try {
            $sql = 'SELECT * FROM species';
            $db = $this->connection->prepare($sql);
            $db->execute();
            $species = $db->fetchAll();
            return $species;
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}