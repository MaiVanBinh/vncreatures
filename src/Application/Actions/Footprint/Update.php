<?php

namespace App\Application\Actions\Footprint;

use App\Application\Actions\Footprint\FootprintAction;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class Update extends FootprintAction
{
    public function action()
    {
        try {
            $token = $this->request->getAttribute('token');
            $isUserExist = false;
            if ($token) {
                $isUserExist = $this->checkUserExist($token['id']);
            }
            if ($isUserExist) {
                $this->validator->validate($this->request, [
                    "name_vn" => v::notEmpty(),
                    "name_latin" => v::notEmpty(),
                    "name_en" => v::notEmpty(),
                    "avatar" => v::digit()
                ]);
                $id = $this->resolveArg('id');
                $footprint = $this->footprintServices->fetchFootprintById($id);
                if (count($footprint) === 0) {
                    return $this->respondWithData("Footprint not found", 400);
                }
                $avatarNew = CustomRequestHandler::getParam($this->request, "avatar");
                $avatar = $footprint[0]['avatar'];
                if ($this->validator->failed()) {
                    $responseMessage = $this->validator->errors;
                    return $this->respondWithData($responseMessage, 404);
                }
                $name_vn = CustomRequestHandler::getParam($this->request, "name_vn");
                $name_latin = CustomRequestHandler::getParam($this->request, "name_latin");
                $name_en = CustomRequestHandler::getParam($this->request, "name_en");

                $creature = intval(CustomRequestHandler::getParam($this->request, "id"));
                $this->footprintServices->update($id, $name_vn, $name_latin, $name_en, $avatarNew, $creature, $token['id']);
                if ($avatar != $avatarNew) {
                    $this->unLinkImage($avatar);
                    $this->assetsServices->useImage($avatarNew, true);
                }
                return $this->respondWithData($name_vn);
            } else {
                return $this->respondWithData('Unauthorzied', 401);
            }
        } catch (Exception $e) {
            return $this->respondWithData($e->getMessage(), 500);
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}
