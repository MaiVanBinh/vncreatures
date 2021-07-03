<?php

namespace App\Application\Actions\Category;

use App\Application\Actions\Category\CategoryAction;
use Psr\Http\Message\UploadedFileInterface;
use App\Exception\ValidationException;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

final class DeteleCatogory extends CategoryAction
{
    /**
     * {@inheritdoc}
     */
    protected function action() {
        try {
            $token = $this->request->getAttribute('token');
            $valid = $this->checkUserExist($token['id']);
            if(!$valid) {
                return $this->respondWithData('Not Auth', 401);
            } 
            $id = $this->resolveArg('id');

            $userExist = $this->checkUserExist($token['id']);
            if(!$userExist) {
                return $this->respondWithData('Not Auth', 401);
            }

            $posts = $this->checkCateHasPosts($id);

            if($posts) {
                return $this->respondWithData(['message' => 'Delete Not success', 'Posts of category:' => $posts], 400);
            }
            $this->categoryServices->delete($id);
            return $this->respondWithData('Delete category success', 200);

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