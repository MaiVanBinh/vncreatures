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

final class ChangePasswordRequest extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action() {
        try {
            
            
            if($this->validator->failed())
            {
                $responseMessage = $this->validator->errors;
                return $this->respondWithData($responseMessage, 400);
            }
            $email = CustomRequestHandler::getParam($this->request,"email");

            $user = $this->userServices->findUserByEmail($email);
            $token = User::generateToken($user['id'],true);
            // $this->userServices->userChangePassWaiting($token, $user['id']);
            return $this->respondWithData($token, 200);
        } catch(Exception $ex) {
            // return $this->respondWithData("User not found", 404);
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }

    function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
    {
        $filename = $uploadedFile->getClientFilename();
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}