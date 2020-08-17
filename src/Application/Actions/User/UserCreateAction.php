<?php

namespace App\Application\Actions\User;

use Slim\Exception\HttpBadRequestException;
use App\Application\Actions\User\UserAction;
use Psr\Http\Message\UploadedFileInterface;
use Psr\Container\ContainerInterface;

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
            throw new HttpBadRequestException($this->request, $e->getMessage());
        }
    }

    function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
    {
        $filename = $uploadedFile->getClientFilename();
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}