<?php

namespace App\Domain\Wood;

use PDO;

class WoodServices {
    private $connection;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    public function fetchWoodForm($page = 1) {
        $offset = ($page - 1) * 40;
        $sql = "SELECT * from wood_form limit 40 offset {$offset}";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $wood_form = $db->fetchAll();
        return $wood_form;
    }
}