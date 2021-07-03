<?php

namespace App\Application\Actions\User;

use App\Application\Actions\User\UserAction;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use App\Models\User;

final class Login extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action()
    {
        try {
            $this->validator->validate($this->request, [
                "username" => v::notEmpty()->length(5, 20),
                "password" => v::notEmpty()->alpha('1', '2', '3', '4', '5', '6', '7', '8', '9', '0')->Length(8, 16)
            ]);

            if ($this->validator->failed()) {
                $responseMessage = $this->validator->errors;
                return $this->respondWithData($responseMessage, 400);
            }
            
            $username = CustomRequestHandler::getParam($this->request, "username");
            $password = CustomRequestHandler::getParam($this->request, "password");

            $user = $this->userServices->findUserByUsername($username);

            $verify = password_verify($password, $user['password']);

            if ($verify == false) {
                throw new HttpNotFoundException($this->request, 'User name or password wrong');
            }

            $responseMessage = User::generateToken($user['id']);

            unset($user['password']);
            return $this->respondWithData(['token' =>$responseMessage, 'expirationDate' => 3600, 'user' => $user], 200);
            
        } catch (Exception $ex) {
            if ($ex instanceof HttpNotFoundException) {
                return $this->respondWithData(['err' => $ex->getMessage()], 404);
            }
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }
}