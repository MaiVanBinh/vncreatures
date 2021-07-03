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
            'password' => $user->hashPassword
        ];

        $sql = "INSERT INTO users SET 
            username=:username, 
            password=:password;";

        $this->connection->prepare($sql)->execute($row);

        return ['id' => (int)$this->connection->lastInsertId()];
    }

    public function findUserByUsername($username) {
        $sql = "SELECT * FROM users WHERE users.username=:username;";
        $db = $this->connection->prepare($sql);
        $db->execute(['username' => $username]);
        $users = $db->fetchAll();
        if(count($users) > 0) {
            return $users[0];
        }
        throw new Exception('User not found');
    }

    public function findUserById($id) {
        $sql = 'SELECT id, username, role FROM users WHERE id=:id;';
        $db = $this->connection->prepare($sql);
        $db->execute(['id' => $id]);
        $users = $db->fetchAll();
        return $users;
    }

    public function checkUserIsSuperAdmin($id) {
        $sql = 'SELECT id, username FROM users WHERE id=:id and role ="1";';
        $db = $this->connection->prepare($sql);
        $db->execute(['id' => $id]);
        $users = $db->fetchAll();
        return $users;
    }

    public function fetchUser($page = 1, $limit = 1, $name=1) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT id, username, role, state, created_by, created_at, updated_at from users where username like :nameString LIMIT :limit offset :offset;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':offset', $offset, PDO::PARAM_INT);
        $db->bindParam(':limit', $limit, PDO::PARAM_INT);
        $nameString = '%' . $name . '%';
        $db->bindParam(':nameString', $nameString, PDO::PARAM_STR);
        $db->execute();
        $users = $db->fetchAll();

        $sqlCount = "SELECT count(id) as total from users where username like :nameString";
        $db = $this->connection->prepare($sqlCount);
        $db->bindParam(':nameString', $nameString, PDO::PARAM_STR);
        $db->execute();
        $total = $db->fetchAll()[0]['total'];

        return ['total' => $total, 'users' => $users];
    }

    public function deleteUser($id) {
        $sql = "UPDATE users set state = 0 where id=:id and role != '1';";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
    }

    public function userChangePassWaiting($token, $id) {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date("Y-m-d H:i:s", time()+3600);
        $sql = "UPDATE users set token=:token, expired=:expired where id=:id";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':token', $token, PDO::PARAM_STR);
        $db->bindParam(':expired', $date, PDO::PARAM_STR);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
    } 

    public function changePassword($id, $password) {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date("Y-m-d H:i:s", time()+3600);
        $sql = "UPDATE users set password=:password, updated_at=:updated_at where id=:id";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':password', $password, PDO::PARAM_STR);
        $db->bindParam(':updated_at', $date, PDO::PARAM_STR);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
    }
}