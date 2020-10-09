<?php

namespace App\Domain\User;

use App\Domain\User\UserRepository;
use App\Models\User;
use Exception;
use PDO;
use Slim\Exception\HttpNotFoundException;

/**
 * Service.
 */
class UserServices
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function register(User $user) {

        $row = [
            'username' => $user->username,
            'email' => $user->email,
            'password' => $user->hashPassword
        ];

        $sql = "INSERT INTO users SET 
            username=:username, 
            email=:email, 
            password=:password;";

        $this->connection->prepare($sql)->execute($row);

        return ['id' => (int)$this->connection->lastInsertId()];
    }

    public function emailHasExist($email) {
        $sql = "SELECT count(:email) AS 'total' FROM users WHERE email=:email;";
        $db = $this->connection->prepare($sql);
        $db->execute(['email' => $email]);
        $total = $db->fetchAll();
        if(intval($total[0]['total']) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function findUserByEmail($email) {
        $sql = "SELECT id, username, email, password FROM users WHERE users.email=:email;";
        $db = $this->connection->prepare($sql);
        $db->execute(['email' => $email]);
        $users = $db->fetchAll();
        if(count($users) > 0) {
            return $users[0];
        }
        throw new Exception('User not found');
    }

    public function findUserById($id) {
        $sql = 'SELECT id, username, email FROM users WHERE id=:id;';
        $db = $this->connection->prepare($sql);
        $db->execute(['id' => $id]);
        $users = $db->fetchAll();
        return $users;
    }
}