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
     * @param int loaiId The loaiId of Species
     * @return array classes The name of classes list
     */
    public function classesListBySpecies($loaiId) {
        $sql = 'SELECT * FROM nhom where Loai=:loaiId';
        $db = $this->connection->prepare($sql);
        $db->execute(['loaiId' => $loaiId]);
        $classes = $db->fetchAll();
        return $classes;
    }
}