<?php

namespace App\Application\Actions\User;

use App\Application\Actions\User\UserAction;
use Psr\Http\Message\UploadedFileInterface;
use App\Exception\ValidationException;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Exception;
use App\Models\User;
use Slim\Exception\HttpInternalServerErrorException;

final class Register extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action() {
        try {
            $this->validator->validate($this->request, [
                "username"=>v::notEmpty(),
                "email"=>v::notEmpty()->email(),
                "password"=>v::notEmpty()->alpha('1','2','3','4','5', '6', '7', '8', '9', '0')->Length(8, 16)
            ]);
            
            if($this->validator->failed())
            {
                $responseMessage = $this->validator->errors;
                return $this->respondWithData($responseMessage, 404);
            }
            
            $name = CustomRequestHandler::getParam($this->request,"username");
            $email = CustomRequestHandler::getParam($this->request,"email");
            $password = CustomRequestHandler::getParam($this->request,"password");
            $passwordHash = User::hashPassword($password);

            if($this->userServices->emailHasExist($email)) {
                $responseMessage = "this email already exists";
                return $this->respondWithData($responseMessage, 400);
            }
            
            $user = new User($name, $email, $passwordHash);
            $id = $this->userServices->register($user);
            return $this->respondWithData($id, 201);

        } catch(Exception $ex) {
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }

    function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
    {
        $filename = $uploadedFile->getClientFilename();
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }

    /**
     * Input validation.
     *
     * @param array $data The form data
     *
     * @throws ValidationException
     *
     * @return void
     */
    private function validateNewUser(array $data)
    {
        $errors = '';

        // Here you can also use your preferred validation library

        if (empty($data['username'])) {
            $errors .= "username input required.\n";
        }

        if (empty($data['email'])) {
            $errors .= "email input required.\n";
        } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors .= "Invalid email address.\n";
        }

        return $errors;
    }
}