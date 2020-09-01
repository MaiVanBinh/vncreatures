<?php

namespace App\Application\Actions\User;

use Slim\Exception\HttpBadRequestException;
use App\Application\Actions\User\UserAction;
use Psr\Http\Message\UploadedFileInterface;
use App\Exception\ValidationException;
use Slim\Exception\RequestBodyValidationError;
use Exception;

final class UserCreateAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action() {
        try {
            // Collect input from the HTTP request
            $data = (array) $this->request->getParsedBody();
            
            // validate error
            $errors = (string) $this->validateNewUser($data);

            if($errors !== '') {
                throw new RequestBodyValidationError($this->request, $errors);
            }
            //upload file
            $directory = __DIR__ . '/../../../../assets/images';

            // Get all file upload
            $uploadedFiles = $this->request->getUploadedFiles();

            // Get a single file
            $uploadedFile = $uploadedFiles['file'];

            $filename = null;
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $filename = $this->moveUploadedFile($directory, $uploadedFile);
            }
            if(!$filename) {
                throw new Exception('Upload image error');
            }
            
            $data['image'] = $filename;

            // Invoke the Domain with inputs and retain the result
            $userId = $this->userServices->createUser($data);

            // Transform the result into the JSON representation
            $result = [
                'user_id' => $userId
            ];
            $this->logger->info('User is created', $result);
        
            // Create and return response
            return $this->respondWithData($result, 201);
        } catch(Exception $e) {
            throw $e;
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