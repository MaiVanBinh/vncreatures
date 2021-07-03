<?php 
namespace App\Domain\LatinDic;
use PDO;

class LatinDicRepository {
    private $connection;
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function latinToViet($keyWord) {
        $sql = "SELECT id, latin, viet FROM latin_dic WHERE latin like :keyWord limit 5;";
        $titleString = '%' . $keyWord . '%';
        $db = $this->connection->prepare($sql);
        $db->bindParam(':keyWord', $titleString, PDO::PARAM_STR);
        $db->execute();
        $result = $db->fetchAll();
        return $result;
    }

    public function VietToLatin($keyWord) {
        $sql = "SELECT id, latin, viet FROM latin_dic WHERE viet like :keyWord limit 5;";
        $titleString = '%' . $keyWord . '%';
        $db = $this->connection->prepare($sql);
        $db->bindParam(':keyWord', $titleString, PDO::PARAM_STR);
        $db->execute();
        $result = $db->fetchAll();
        return $result;
    }
}