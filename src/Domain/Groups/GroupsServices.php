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
    public function fetchGroup($entires = null, $page = 1, $isFilter = false, $filter = null)
    {
        if ($isFilter) {
            $sql = "select id, name_vn, species FROM vncreatu_vncreature_new.group order by name_vn asc;";
            $db = $this->connection->prepare($sql);
            $db->execute();
            $groups = $db->fetchAll();
            return $groups;
        }
        // $sql = "SELECT 
        //     g.*, 
        //     u1.username as created_by_name, 
        //     u2.username as updated_by_name, 
        //     s.name_vn, s.name_vn as species_name 
        // FROM vncreatu_vncreature_new.group g, 
        //     users u1, 
        //     users u2, 
        //     species s 
        // where g.created_by = u1.username 
        //     and g.id=1
        //     and g.updated_by = u2.username 
        //     and s.id = g.species 
        // order by g.name_vn asc;";
        $sqlCount = "select count(id) as total FROM vncreatu_vncreature_new.group;";
        $db = $this->connection->prepare($sqlCount);
        $db->execute();
        $total = $db->fetchAll();
        if ($entires) {
            $offset = ($page - 1) * $entires;
            $sql = "SELECT g.*, 
                u1.username as created_by_name, 
                u2.username as updated_by_name, 
                s.name_vn as species_name 
            FROM vncreatu_vncreature_new.group g, 
                users u1, 
                users u2, 
                species s 
            where 
                g.created_by = u1.id 
                and g.updated_by = u2.id 
                and s.id = g.species";
            if($filter && $filter['value']) {
                if($filter['name'] === 'species') {
                    $sql .= " and s.name_vn like '%{$filter['value']}%'";
                } else if($filter['name'] === 'id') {
                    $sql .= " and g.id={$filter['value']}";
                } else if($filter['name'] === 'created_by') {
                    $sql .= " and u1.username like '%{$filter['value']}%'";
                } else if($filter['name'] === 'updated_by') {
                    $sql .= " and u2.username like '%{$filter['value']}%'";
                } else {
                    $sql .= " and g.{$filter['name']} like '%{$filter['value']}%'";
                }
            }
            $sql .= " order by g.name_vn asc limit {$entires} offset {$offset};";
        }
        $db = $this->connection->prepare($sql);
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
        $sql = "SELECT COUNT(id) AS total FROM vncreatu_vncreature_new.group where species={$speciesId}";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $result = $db->fetchAll();
        $total = $result[0]['total'];
        return $total;
    }

    public function create($name_vn, $name_latin, $species, $userId)
    {
        try {
            $sql = "INSERT INTO vncreatu_vncreature_new.group (name_vn, name_latin, species, created_by, updated_by) values ('{$name_vn}', '{$name_latin}', {$species}, {$userId}, {$userId})";

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
    FROM vncreatu_vncreature_new.group g, 
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
            $sql = "DELETE FROM vncreatu_vncreature_new.group WHERE id={$id}";
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
            $sql = "UPDATE vncreatu_vncreature_new.group 
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
}
