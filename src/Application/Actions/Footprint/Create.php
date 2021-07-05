<?php
namespace App\Application\Actions\Footprint;

use App\Application\Actions\Footprint\FootprintAction;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class Create extends FootprintAction {
    public function action() {
        try {
            $token = $this->request->getAttribute('token');
            $isUserExist = false;
            if($token) {
                $isUserExist = $this->checkUserExist($token['id']);
            }
            if($isUserExist) {
                $this->validator->validate($this->request, [
                    "name_vn"=>v::notEmpty(),
                    "name_latin"=>v::notEmpty(),
                    "name_en"=>v::notEmpty(),
                    "avatar"=>v::digit()
                ]);
                if($this->validator->failed())
                {
                    $responseMessage = $this->validator->errors;
                    return $this->respondWithData($responseMessage, 404);
                }
                $name_vn = CustomRequestHandler::getParam($this->request, "name_vn");
                $name_latin = CustomRequestHandler::getParam($this->request, "name_latin");
                $name_en = CustomRequestHandler::getParam($this->request, "name_en");
                $avatar = CustomRequestHandler::getParam($this->request, "avatar");
                $creature = intval(CustomRequestHandler::getParam($this->request, "id"));
                $this->footprintServices->createFootprint($name_vn, $name_latin, $name_en, $avatar, $creature, $token['id']);
                $this->assetsServices->useImage($avatar, true);
                return $this->respondWithData($name_vn);
            } else {
                return $this->respondWithData('Unauthorzied', 401);
            }
            
        } catch(Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}