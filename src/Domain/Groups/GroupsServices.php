<?php

namespace App\Domain\Groups;

use Exception;
use PDO;

class GroupsServices
{
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
    public function fetchGroup($entires = null, $page = 1, $isFilter = false, $name = '', $species = null)
    {
        $sql = "select id, name_vn, name_latin, species, created_at FROM vncreatu_vncreatures.group order by name_vn asc;";
        if ($isFilter) {
            $sql = "select id, name_vn, name_latin, species FROM vncreatu_vncreatures.group order by name_vn asc;";
            $db = $this->connection->prepare($sql);
            $db->execute();
            $groups = $db->fetchAll();
            return $groups;
        }
        
        $sql = "select g.*, s.name_vn as speciesVn from vncreatu_vncreatures.group g, species s where s.id=g.species and g.name_vn like :nameString";
        $sqlCount = "select count(id) as total from vncreatu_vncreatures.group where name_vn like :nameString";
        $db = $this->connection->prepare($sql);
        $dbCount = $this->connection->prepare($sqlCount);
        
        if($species) {
            $sql = "select g.*, s.name_vn as speciesVn from vncreatu_vncreatures.group g, species s where s.id=g.species and g.name_vn like :nameString and g.species=:species";
            $sqlCount = "select count(id) as total from vncreatu_vncreatures.group where name_vn like :nameString and species=:species";
            $db = $this->connection->prepare($sql);
            $dbCount = $this->connection->prepare($sqlCount);
            $db->bindParam(':species', $species, PDO::PARAM_INT);
            $dbCount->bindParam(':species', $species, PDO::PARAM_INT);
        }
        $nameString = '%' . $name . '%';
        $db->bindParam(':nameString', $nameString, PDO::PARAM_STR);
        $dbCount->bindParam(':nameString', $nameString, PDO::PARAM_STR);
        $dbCount->execute();
        $total =  $dbCount->fetchAll();
        $db->execute();
        $Groups = $db->fetchAll();
        return ['total' => $total[0]['total'], 'groups' => $Groups];
    }

    /**
     * @param int loaiId The loaiId of Species
     * @return array Groups The name of Groups list
     */
    public function countBySpecies($speciesId)
    {
        $sql = "SELECT COUNT(id) AS total FROM vncreatu_vncreatures.group where species={$speciesId}";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $result = $db->fetchAll();
        $total = $result[0]['total'];
        return $total;
    }

    public function create($name_vn, $name_latin, $species, $userId)
    {
        try {
            $sql = "INSERT INTO vncreatu_vncreatures.group (name_vn, name_latin, species, created_by, updated_by) values ('{$name_vn}', '{$name_latin}', {$species}, {$userId}, {$userId})";

            // return $sql;
            $this->connection->prepare($sql)->execute();
            return (int)$this->connection->lastInsertId();
        } catch (Exception $ex) {
            throw $ex->getMessage();
        }
    }

    public function fetchGroupsById($id)
    {
        $sql = "SELECT g.*, 
        u1.username as created_by_name, 
        u2.username as updated_by_name, 
        s.name_vn as species_name 
    FROM vncreatu_vncreatures.group g, 
        users u1, 
        users u2, 
        species s 
    where 
        g.created_by = u1.id 
        and g.updated_by = u2.id 
        and s.id = g.species and g.id=:id";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
        $groups = $db->fetchAll();
        return $groups;
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM vncreatu_vncreatures.group WHERE id={$id}";
            $db = $this->connection->prepare($sql);
            $db->execute();
            // return $sql;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function update($id, $name_vn, $name_latin, $species, $userId)
    {
        try {
            $date = date('Y-m-d H:i:s');
            $sql = "UPDATE vncreatu_vncreatures.group 
                    SET 
                        name_vn='{$name_vn}', 
                        name_latin='{$name_latin}',
                        species={$species},
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

    public function countGroups() {
        $sql = "SELECT COUNT(id) AS total FROM vncreatu_vncreatures.group;";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $result = $db->fetchAll();
        $total = $result[0]['total'];
        return $total;
    }
}