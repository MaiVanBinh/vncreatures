<?php

namespace App\Domain\Creatures;

use App\Domain\Creatures;
use Exception;
use PDO;

class CreaturesRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    /**
     * @param array Filter include id cua loai, nhom, bo va ho
     */




    public function getCreaturesByFilter($filter)
    {
        define('NUMBER_PER_PAGE', 9);
        $sqlSelectPath = [];
        $sqlCountPath = [];
        $name = '';
        array_push($sqlSelectPath, "SELECT 
            c.id, 
            c.name_vn, 
            c.name_latin, 
            g.name_vn as group_vn, 
            f.name_vn as family_vn, 
            o.name_vn as order_vn, 
            c.author , 
            c.avatar, 
            c.redbook_level, 
            s.name_vn as species_vn,
            a.name,
            c.created_at,
            c.created_by
        from (SELECT 
                creatures.id, 
                creatures.name_vn, 
                creatures.name_latin, 
                creatures.group, creatures.family, 
                creatures.order, 
                creatures.species, 
                creatures.avatar,
                author,
                creatures.created_at,
                creatures.created_by,
                redbook_level FROM creatures WHERE name_vn !='vodanh'");
        array_push($sqlCountPath, "SELECT count(id) as total from creatures WHERE name_vn!='vodanh'");
        if (array_key_exists('family', $filter) && count(json_decode($filter['family'])) > 0) {
            $families = '(' . join(", ", json_decode($filter['family'])) . ')';
            array_push($sqlSelectPath, "AND creatures.family in {$families}");
            array_push($sqlCountPath, "AND creatures.family in {$families}");
        } else if (array_key_exists('order', $filter) && count(json_decode($filter['order'])) > 0) {
            $order = '(' . join(", ", json_decode($filter['order'])) . ')';
            array_push($sqlSelectPath, "AND creatures.order in {$order}");
            array_push($sqlCountPath, "AND creatures.order in {$order}");
        } else if (array_key_exists('group', $filter) && count(json_decode($filter['group'])) > 0) {
            $groups = '(' . join(", ", json_decode($filter['group'])) . ')';
            array_push($sqlSelectPath, "AND creatures.group in {$groups}");
            array_push($sqlCountPath, "AND creatures.group in {$groups}");
        } else if (array_key_exists('species', $filter) && $filter['species'] && $filter['species'] !== -1) {
            array_push($sqlSelectPath, "AND creatures.species = {$filter['species']}");
            array_push($sqlCountPath, "AND creatures.species = {$filter['species']}");
        }
        if (array_key_exists('name', $filter) && $filter['name'] !== '') {
            $name = html_entity_decode($filter["name"], ENT_NOQUOTES, 'UTF-8');
            array_push($sqlSelectPath, "AND name_vn like N'%{$name}%'");
            array_push($sqlCountPath, "AND creatures.name_vn like N'%{$name}%';");
        }
        $offset = array_key_exists('page', $filter) ? (intval($filter['page']) - 1) * 9 : 0;
        $limit = array_key_exists('limit', $filter) ? intval($filter['limit']) : 30;
        array_push($sqlSelectPath, "LIMIT {$limit}  OFFSET {$offset}");
        array_push($sqlSelectPath, ") c, vncreatu_vncreature_new.group g, vncreatu_vncreature_new.family f, vncreatu_vncreature_new.orders o, vncreatu_vncreature_new.species as s, vncreatu_vncreature_new.author as a where c.group = g.id and c.family = f.id and c.order = o.id and c.species = s.id and c.author = a.id;");

        $sql = join(' ', $sqlCountPath);
        $db = $this->connection->prepare($sql);
        $db->execute();
        $total = $db->fetchAll();

        $sql = join(' ', $sqlSelectPath);
        $db = $this->connection->prepare($sql);
        $db->execute();
        $creatures = $db->fetchAll();
        return ['total' => $total[0]['total'], 'creatures' => $creatures, 'name' => $name];
        return ['sql' => $sql];
    }

    /**
     * @param int id The id of creatures
     * 
     * @return array The information of creatures
     */
    public function FindCreaturesById($id)
    {
        $sql = "SELECT 
            c.id, 
            c.name_vn, 
            c.name_latin,
            c.description,
            g.name_vn as group_vn, 
            f.name_vn as family_vn, 
            o.name_vn as order_vn,
            s.name_en as species
        from 
            (select * from vncreatu_vncreature_new.creatures c where c.id =:id) c, 
            vncreatu_vncreature_new.group g, 
            vncreatu_vncreature_new.family f, 
            vncreatu_vncreature_new.orders o, 
            vncreatu_vncreature_new.species s
        where c.group = g.id and c.family = f.id and c.order = o.id and c.species = s.id;";

        $db = $this->connection->prepare($sql);
        $db->execute(['id' => $id]);
        $creatures = $db->fetchAll();
        if (count($creatures) < 0) {
            throw new Exception("Creatures not found");
        }
        return $creatures[0];
    }

    public function fetchCreatureRedBook($filter)
    {
        $sql = "SELECT id, name_vn, name_latin, redbook_level FROM creatures where redbook_level is not null ";
        if (array_key_exists('species', $filter) && $filter['species']) {
            $sql .= "AND species={$filter['species']} ";
        }
        $sql .= " ORDER BY name_vn asc";
        if (!array_key_exists('all', $filter)) {
            $sql .= " LIMIT 10;";
        }
        $db = $this->connection->prepare($sql);
        $db->execute();
        $creatures = $db->fetchAll();
        return $creatures;
        return ['sql' => $sql];
    }
}
