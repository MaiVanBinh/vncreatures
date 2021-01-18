<?php

namespace App\Domain\Creatures;
use PDO;
use Exception;

class CreaturesServices {
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
        $limit = array_key_exists('limit', $filter) ? intval($filter['limit']) : 10;
        array_push($sqlSelectPath, "LIMIT {$limit}  OFFSET {$offset}");
        array_push($sqlSelectPath, ") c, vncreatu_vncreature_new.group g, vncreatu_vncreature_new.families f, vncreatu_vncreature_new.orders o, vncreatu_vncreature_new.species as s, vncreatu_vncreature_new.author as a where c.group = g.id and c.family = f.id and c.order = o.id and c.species = s.id and c.author = a.id;");

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
    public function fetchCreatureById($id)
    {
        $sql = "SELECT 
            c.*,
            g.name_vn as group_vn, 
            f.name_vn as family_vn, 
            o.name_vn as order_vn,
            s.name_vn as species_vn,
            u1.username as created_by,
            u2.username as updated_by,
            a.name as author_name
        from 
            (select * from vncreatu_vncreature_new.creatures c where c.id =:id) c, 
            vncreatu_vncreature_new.group g, 
            vncreatu_vncreature_new.families f, 
            vncreatu_vncreature_new.orders o, 
            vncreatu_vncreature_new.species s,
            vncreatu_vncreature_new.author a,
            vncreatu_vncreature_new.users u1,
            vncreatu_vncreature_new.users u2
        where 
            c.group = g.id 
            and c.family = f.id 
            and c.order = o.id 
            and a.id = c.author 
            and u1.id = c.created_by
            and u2.id = c.updated_by 
            and c.species = s.id;";

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
        $sql = "SELECT id, name_vn, name_latin, redbook_level, avatar FROM creatures where redbook_level is not null AND avatar is not null ";
        if (array_key_exists('species', $filter) && $filter['species']) {
            $sql .= "AND species={$filter['species']} ";
        }
        $sql .= " ORDER BY name_vn asc";
        if (!array_key_exists('all', $filter)) {
            $sql .= " LIMIT 4;";
        }
        $db = $this->connection->prepare($sql);
        $db->execute();
        $creatures = $db->fetchAll();
        return $creatures;
        return ['sql' => $sql];
    }

    public function editCreatureById($creatures, $userUpdateId) {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date("Y-m-d H:i:s");
        $sql = "UPDATE creatures c SET name_vn='{$creatures["name_vn"]}', name_latin='{$creatures["name_latin"]}', species={$creatures['species']},family={$creatures['family']},c.order={$creatures['order']},c.group={$creatures['group']},description='{$creatures["description"]}',avatar='{$creatures["avatar"]}',author={$creatures['author']},redbook_level='{$creatures["redbook_level"]}',updated_by={$userUpdateId}, updated_at='{$date}' WHERE id={$creatures['id']};";
        $db = $this->connection->prepare($sql);
        $db->execute();
    }

    public function createCreature($creatures, $userUpdateId) {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date("Y-m-d H:i:s");
        $sql = "INSERT into
            creatures (name_vn, name_latin, species, family, creatures.order, creatures.group, description, avatar, author, redbook_level, created_by, created_at,updated_by, updated_at) 
        VALUES (
            '{$creatures["name_vn"]}', 
            '{$creatures["name_latin"]}', 
            {$creatures['species']}, 
            {$creatures['family']}, 
            {$creatures['order']}, 
            {$creatures['group']}, 
            '{$creatures["description"]}', 
            '{$creatures["avatar"]}', 
            {$creatures['author']}, 
            '{$creatures["redbook_level"]}', 
            {$userUpdateId},
            '{$date}',
            {$userUpdateId},
            '{$date}'
            )";
        $db = $this->connection->prepare($sql);
        $db->execute();
        return (int)$this->connection->lastInsertId();
    }

    public function countByFamily($familyId) {
        $sql = "SELECT COUNT(id) AS total FROM vncreatu_vncreature_new.creatures where family={$familyId}";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $result = $db->fetchAll();
        $total = $result[0]['total'];
        return $total;
    }
    
    public function deleteCreature($id) {
        $sql = "DELETE FROM creatures WHERE id=:id";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
    }
};

