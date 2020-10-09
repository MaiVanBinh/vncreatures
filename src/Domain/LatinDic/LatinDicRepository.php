<?php 
namespace App\Domain\LatinDic;
use PDO;

class LatinDicRepository {
    private $connection;
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function fetchLatinDic($keyWord) {
        $sql = "SELECT id, latin, viet FROM latin_dic WHERE latin=:keyWord;";
        $db = $this->connection->prepare($sql);
        $db->execute(['keyWord' => $keyWord]);
        $latinWord = $db->fetchAll();
        return $latinWord;
    }
}