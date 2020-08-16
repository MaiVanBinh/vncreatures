<?php

namespace App\Domain\User\Repository;
use PDO;


class UserListRepository {
    private $connection;
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    /**
     * Find list users.
     *
     * @return array The new ID
     */
    public function listUser() {
        $sql = "SELECT * FROM users LIMIT 10";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $users = $db->fetchAll(\PDO::FETCH_ASSOC);
        return $users;
    }
}