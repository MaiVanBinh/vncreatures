<?php

namespace App\Domain\User;

use App\Application\Actions\Posts\UpdatePost;
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

    public function checkUserIsSuperAdmin($id) {
        $sql = 'SELECT id, username, email FROM users WHERE id=:id and role ="1";';
        $db = $this->connection->prepare($sql);
        $db->execute(['id' => $id]);
        $users = $db->fetchAll();
        return $users;
    }

    public function fetchUser($page = 1) {
        $offset = ($page - 1) * 10;
        $sql = "SELECT id, username, email, role, created_by, created_at, updated_at from users where state = 1 limit 10 offset :offset;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':offset', $offset, PDO::PARAM_INT);
        $db->execute();
        $users = $db->fetchAll();
        return $users;
    }

    public function deleteUser($id) {
        $sql = "UPDATE users set state = 0 where id=:id;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
    }
}