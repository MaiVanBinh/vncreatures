<?php

namespace App\Domain\User\Repository;

use PDO;
use PDOException;

class UserDeletionRepository {
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;    
    }

    /**
     * Delete user by id
     * 
     * @param int id The user id
     * 
     * @return int id The user id is deleted
     */
    public function deleteUser($id) {
        try{
            $sql = 'DELETE FROM users WHERE id=:id';
            $this->connection->prepare($sql)->execute(['id' => $id]);
            return $id;
        } catch(PDOException $e) {
            return $e;
        }
    }
} 