<?php

namespace App\Domain\User; 

use PDO;
use Exception;

class UserRepository {
    /**
     * @var PDO The database connection
     */
    private $connection;

    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Insert user row.
     *
     * @param array $user The user
     *
     * @return int The new ID
     */
    public function insertUser(array $user): int
    {
        $row = [
            'username' => $user['username'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'email' => $user['email'],
            'imageUrl' => $user['image'],
        ];

        $sql = "INSERT INTO users SET 
                username=:username, 
                first_name=:first_name, 
                last_name=:last_name, 
                email=:email,
                imageUrl=:imageUrl;";
        try{
            $this->connection->prepare($sql)->execute($row);
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
        
        return (int)$this->connection->lastInsertId();
    }

    /**
     * Delete user by id
     * 
     * @param int id The user id
     * 
     * @return int id The user id is deleted
     */
    public function deleteUser($id) 
    {
        $sql = 'DELETE FROM users WHERE id=:id';
        $this->connection->prepare($sql)->execute(['id' => $id]);
        return $id;
    }

    /**
     * Delete user by id
     * 
     * @param int id The user id
     * 
     * @return int id The user id is deleted
     */
    public function findUserById($id) 
    {
        $sql = 'SELECT * FROM users WHERE id=:id';
        $db = $this->connection->prepare($sql);
        $db->execute(['id' => $id]);
        $user = $db->fetchAll(\PDO::FETCH_ASSOC);
        if(!empty($user)) {
            return $user[0];
        } else {
            throw  new Exception('User not found');
        }   
        
    }

    /**
     * Find list users.
     *
     * @return array The new ID
     */
    public function listUser() 
    {
        $sql = "SELECT * FROM users LIMIT 10";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $users = $db->fetchAll(\PDO::FETCH_ASSOC);
        return $users;
    }

    /**
     * @param array user The information update
     * 
     * @return int id The id of user udpate
     */
    public function updateUser($userUpdate) {
        $id = $userUpdate['id'];
        $sql = "SELECT * FROM users WHERE id = $id";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $user = ($db->fetchAll());
        if(!empty($user)) {
            $user = $user[0];
        } else {
            throw new Exception('User not found');
        }
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
    }
}