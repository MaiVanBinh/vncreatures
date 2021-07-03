<?php

namespace App\Application\Actions\User;

use App\Application\Actions\User\UserAction;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Exception;
use App\Models\User;
use Slim\Exception\HttpInternalServerErrorException;


class ChangePassword extends UserAction
{
    public function action()
    {
        try {
            $token = $this->request->getAttribute('token');
            $this->validator->validate($this->request, [
                "password" => v::notEmpty()->alpha('1', '2', '3', '4', '5', '6', '7', '8', '9', '0')->Length(8, 16)
            ]);

            if ($this->validator->failed()) {
                $responseMessage = $this->validator->errors;
                return $this->respondWithData($responseMessage, 400);
            }

            if (count($this->userServices->findUserById($token['id'])) == 0) {
                $responseMessage = "User Not Found1";
                return $this->respondWithData($responseMessage, 404);
            }
            $password = CustomRequestHandler::getParam($this->request, "password");
            $passwordHash = User::hashPassword($password);
            $id = $this->userServices->changePassword($token['id'], $passwordHash);
            return $this->respondWithData("Change password success", 201);
        } catch (Exception $ex) {
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }
}