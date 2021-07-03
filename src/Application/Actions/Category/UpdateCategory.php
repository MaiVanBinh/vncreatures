<?php

namespace App\Application\Actions\Category;

use App\Application\Actions\Category\CategoryAction;
use Psr\Http\Message\UploadedFileInterface;
use App\Exception\ValidationException;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

final class UpdateCategory extends CategoryAction
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
            $this->validator->validate($this->request, [
                "name_vn"=>v::notEmpty(),
                "name_en"=>v::notEmpty()
            ]);
            
            if($this->validator->failed())
            {
                $responseMessage = $this->validator->errors;
                return $this->respondWithData($responseMessage, 404);
            }
            
            $name_vn = CustomRequestHandler::getParam($this->request,"name_vn");
            $name_en = CustomRequestHandler::getParam($this->request,"name_en");
            $list = CustomRequestHandler::getParam($this->request,"list");
            $list = $list != '1' ? 0 : 1;
            $this->categoryServices->update($id, $name_vn, $name_en,$list, $token['id']);
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