<?php

namespace App\Application\Actions\Creatures;

use Respect\Validation\Validator as v;
use App\Application\Actions\Creatures\CreaturesActions;
use Exception;
use App\Requests\CustomRequestHandler;

class CreateCreature extends CreaturesActions
{
    public function action()
    {
        try {
            $token = $this->request->getAttribute('token');
            if ($token) {
                if (!$this->checkUserExist($token['id'])) {
                    return $this->respondWithData('Unauthorzied', 401);
                };
            }

            $this->validator->validate($this->request, [
                "name_vn" => v::notEmpty(),
                "name_latin" => v::notEmpty(),
                "author" => v::digit(),
                'family' => v::digit(),
                'order' => v::digit(),
                'group' => v::digit(),
                'species' => v::digit(),
            ]);

            if ($this->validator->failed()) {
                $responseMessage = $this->validator->errors;
                return $this->respondWithData($responseMessage, 404);
            }

            $creature = [];
            $creature['name_vn'] = CustomRequestHandler::getParam($this->request, "name_vn");
            $creature['name_latin'] = CustomRequestHandler::getParam($this->request, "name_latin");
            $creature['author'] = CustomRequestHandler::getParam($this->request, "author");
            $creature['description'] = CustomRequestHandler::getParam($this->request, "description");
            $creature['family'] = CustomRequestHandler::getParam($this->request, "family");
            $creature['order'] = CustomRequestHandler::getParam($this->request, "order");
            $creature['group'] = CustomRequestHandler::getParam($this->request, "group");
            $creature['species'] = CustomRequestHandler::getParam($this->request, "species");
            $creature['redbook_level'] = CustomRequestHandler::getParam($this->request, "redbook_level");
            $images = CustomRequestHandler::getParam($this->request, "images");
            if (count($images) > 0) {
                $isImageExist = $this->checkImageExistById($images[0]);
                if ($isImageExist == false) {
                    return $this->respondWithData("Image: {$creature['avatar']} is not exits", 400);
                }
                $creature['avatar'] = $isImageExist;
                
            }
            $id = $this->creaturesServices->createCreature($creature, $token['id']);
            for ($i = 0; $i < count($images); $i++) {
                $isImageExist = (int)$this->checkImageExistById($images[$i]);
                if (!$isImageExist == false) {
                    return $this->respondWithData("Image: {$images[$i]} is not exits", 400);
                }
                $this->acServices->createNewOne($images[$i], (int)$id);
                $this->assetsServices->useImage($images[$i], true);

            }
            return $this->respondWithData($id);
        } catch (Exception $ex) {
            return $this->respondWithData($ex->getMessage(), 400);
        }
    }
}
