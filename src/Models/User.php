<?php

namespace App\Models;

use \Firebase\JWT\JWT;

class User
{
    public $username;
    public $email;
    public $hashPassword;

    public function __construct($username, $email, $hashPassword)
    {
        $this->username = $username;
        $this->email = $email;
        $this->hashPassword = $hashPassword;
    }
    static public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    static public function generateToken($email, $id)
    {
        $now = time();
        $future = strtotime('+1 hour', $now);
        $secret = 'DoAnhBatDuocEm';

        $payload = [
            "id" => $id,
            "email" => $email,
            "iat" => $now,
            "exp" => $future
        ];

        return JWT::encode($payload, $secret, "HS256");
    }
}
