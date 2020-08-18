<?php

namespace App\Domain\Creatures;

use App\Domain\Creatures;

use PDO;

class CreaturesRepository {
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    /**
     * @param array Filter include id cua loai, nhom, bo va ho
     */
    public function getCreaturesByFilter($filter) {
        $sql = "SELECT * FROM creatures";
        $filterUpdate = [];
        if(count($filter) > 0) {
            $sql .= " WHERE";
            $count = 0;
            if(array_key_exists('hoId', $filter)) {
                $count += 1;
                $sql .= " Ho=:hoId";
                $filterUpdate['hoId'] = $filter['hoId'];
            }
            if(array_key_exists('boId', $filter)) {
                if($count > 0){
                    $sql .= " AND";
                }
                $sql .= " Bo=:boId";
                $filterUpdate['boId'] = $filter['boId'];
            }
            if(array_key_exists('nhomId', $filter)) {
                if($count > 0){
                    $sql .= " AND";
                }
                $sql .= " Nhom=:nhomId";
                $filterUpdate['nhomId'] = $filter['nhomId'];
            } 
            if(array_key_exists('loaiId', $filter)) {
                if($count > 0){
                    $sql .= " AND";
                }
                $sql .= " Loai=:loaiId";
                $filterUpdate['loaiId'] = $filter['loaiId'];
            }
        }
        // $sql = "SELECT * FROM vncreatures.creatures WHERE Ho=1 AND Bo=1 AND Nhom=1 AND Loai=1;";
        $db = $this->connection->prepare($sql);
        $db->execute($filterUpdate);
        $creatures = $db->fetchAll();
        return $creatures;
    }
}