<?php

namespace App\Domain\Species;

use PDO;
use Exception;

class SpeciesService
{
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
    public function fetchSpecies($isFilter = false)
    {
        try {
            if ($isFilter) {
                $sql = 'SELECT id, name_vn FROM species';
                $db = $this->connection->prepare($sql);
                $db->execute();
                $species = $db->fetchAll();
                return $species;
            }
            $sql = 'SELECT s.*, u1.username as created_by_name, u2.username as updated_by_name FROM species s, users u1, users u2 where s.created_by = u1.id and s.updated_by = u2.id';
            $db = $this->connection->prepare($sql);
            $db->execute();
            $species = $db->fetchAll();
            return $species;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // create new 
    public function create($name_vn, $name_en, $userId)
    {
        try {
            $sql = "INSERT INTO species (name_vn, name_en, created_by, updated_by) values ('{$name_vn}', '{$name_en}', {$userId}, {$userId})";
            $this->connection->prepare($sql)->execute(['name_vn' => $name_vn, 'name_en' => $name_en]);
            return (int)$this->connection->lastInsertId();
        } catch (Exception $ex) {
            throw $ex->getMessage();
        }
    }

    // delete
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM species WHERE id=:id";
            $db = $this->connection->prepare($sql);
            $db->execute(['id' => $id]);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function update($id, $name_vn, $name_en, $userId)
    {
        try {
            $date = date('Y-m-d H:i:s');
            $sql = "UPDATE species 
                    SET 
                        name_vn='{$name_vn}', 
                        name_en='{$name_en}',
                        updated_by={$userId}, 
                        updated_at='{$date}' 
                        WHERE id={$id}";
            $db = $this->connection->prepare($sql);
            $db->execute();
            // return $sql;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function fetchSpeciesById($id)
    {
        $sql = "SELECT s.*, 
                u1.username as created_by_name, 
                u2.username as updated_by_name 
            FROM species s, users u1, users u2 
            where s.created_by = u1.id and s.updated_by = u2.id and s.id={$id}";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $species = $db->fetchAll();
        return $species;
    }
}
