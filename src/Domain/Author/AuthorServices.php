<?php

namespace App\Domain\Author;
use PDO;

class AuthorServices {
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function fetchAuthors($page, $isAll) {
        $sql = '';
        if($isAll) {
            $sql = "SELECT id, name FROM vncreatu_vncreature_new.author";
        } else {
            $offset = intval($page - 1) * 10;
            $sql = "SELECT id, name FROM vncreatu_vncreature_new.author LIMIT 12 OFFSET  {$offset};";
        }
        $db = $this->connection->prepare($sql);
        $db->execute();
        $authors = $db->fetchAll();
        return $authors;
        $author = $this->repository->fecthAuthors($page);
        return $author;
    }
}