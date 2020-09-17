<?php
namespace App\Domain\Conbine;

use PDO;

class ConbineRespository {
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getFilterData() {
        
    }

}