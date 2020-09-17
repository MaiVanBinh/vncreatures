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
        array_push($sqlSelectPath, "SELECT c.id, c.name_vn, c.name_latin, g.name_vn as group_vn, f.name_vn as family_vn, o.name_vn as order_vn,c.img, s.name_en as species
        from (SELECT creatures.id, creatures.name_vn, creatures.name_latin, creatures.group, creatures.family, creatures.order, creatures.species, creatures.img FROM creatures WHERE name_vn !='vodanh'");
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
        array_push($sqlSelectPath, "LIMIT 9  OFFSET {$offset}");
        array_push($sqlSelectPath, ") c, vncreatures.group g, vncreatures.family f, vncreatures.orders o, vncreatures.species as s where c.group = g.id and c.family = f.id and c.order = o.id and c.species = s.id;");

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
            s.name_en as species,
            AuthorName as author
        from 
            (select * from vncreatures.creatures c where c.id =:id) c, 
            vncreatures.group g, 
            vncreatures.family f, 
            vncreatures.orders o, 
            vncreatures.species s
        where c.group = g.id and c.family = f.id and c.order = o.id and c.species = s.id;";

        $db = $this->connection->prepare($sql);
        $db->execute(['id' => $id]);
        $creatures = $db->fetchAll();
        if (count($creatures) < 0) {
            throw new Exception("Creatures not found");
        }
        return $creatures[0];
    }
}
