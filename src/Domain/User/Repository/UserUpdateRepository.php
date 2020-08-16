<?php

namespace App\Domain\User\Repository;

use PDO;
use PDOException;

class UserUpdateRepository {
    private $connection;
    
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param array user The information update
     * 
     * @return int id The id of user udpate
     */
    public function updateUser($userUpdate) {
        try{
            $id = $userUpdate['id'];
            $sql = "SELECT * FROM users WHERE id = $id";
            $db = $this->connection->prepare($sql);
            $db->execute();
            $user = ($db->fetchAll())[0];
            $user['username'] = $userUpdate['username'] ? $userUpdate['username'] : $user['username'];
            $user['first_name'] = $userUpdate['first_name'] ? $userUpdate['first_name'] : $user['first_name'];
            $user['last_name'] = $userUpdate['last_name'] ? $userUpdate['last_name'] : $user['last_name'];
            $user['email'] = $userUpdate['email'] ? $userUpdate['email'] : $user['email'];
            $sql = "UPDATE users SET 
                username=:username, 
                first_name=:first_name, 
                last_name=:last_name, 
                email=:email WHERE id=:id;";
            $db = $this->connection->prepare($sql);
            $db->execute($user);
            return $id;
        } catch(PDOException $e) {
            throw $e;
        }
    }
}