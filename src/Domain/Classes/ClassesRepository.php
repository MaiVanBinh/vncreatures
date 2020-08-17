<?php

namespace App\Domain\Classes;

use PDO;

class ClassesRepository {
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
     * @param int id The id of Species
     * @return array classes The name of classes list
     */
    public function classesListBySpecies($id) {
        $sql = 'SELECT * FROM nhom where Loai=:id';
        $db = $this->connection->prepare($sql);
        $db->execute(['id' => $id]);
        $classes = $db->fetchAll();
        return $classes;
    }
}