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
                "email" => v::notEmpty()->email(),
                "password" => v::notEmpty()->alpha()
            ]);

            if ($this->validator->failed()) {
                $responseMessage = $this->validator->errors;
                return $this->respondWithData($responseMessage, 404);
            }

            $email = CustomRequestHandler::getParam($this->request, "email");
            $password = CustomRequestHandler::getParam($this->request, "password");

            $user = $this->userServices->findUserByEmail($email);

            $verify = password_verify($password, $user['password']);

            if ($verify == false) {
                throw new HttpNotFoundException($this->request, 'User Not Found');
            }

            $responseMessage = User::generateToken($email, $user['id']);

            return $this->respondWithData(['token' =>$responseMessage, 'expirationDate' => 3600], 200);
            
        } catch (Exception $ex) {
            if ($ex instanceof HttpNotFoundException) {
                return $this->respondWithData(['err' => $ex->getMessage()], 404);
            }
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }
}
