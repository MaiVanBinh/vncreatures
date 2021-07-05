<?php
namespace App\Application\Actions\Wood;

use App\Application\Actions\Wood\WoodAction;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class Update extends WoodAction {
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
                    "img"=>v::digit()
                ]);
                $id =$this->resolveArg('id');
                $wood = $this->woodServices->fetchWoodprintById($id);
                if (count($wood) === 0) {
                    return $this->respondWithData("Wood not found", 400);
                }
                $avatar = $wood[0]['img'];
                $avatarNew = CustomRequestHandler::getParam($this->request, "img");
                if($this->validator->failed())
                {
                    $responseMessage = $this->validator->errors;
                    return $this->respondWithData($responseMessage, 404);
                }
                $name_vn = CustomRequestHandler::getParam($this->request, "name_vn");
                $name_latin = CustomRequestHandler::getParam($this->request, "name_latin");
                $name_en = CustomRequestHandler::getParam($this->request, "name_en");
                $this->woodServices->update($id, $name_vn, $name_latin, $name_en, $avatarNew, $id, $token['id']);
                if ($avatar != $avatarNew) {
                    $this->unLinkImage($avatar);
                    $this->assetsServices->useImage($avatarNew, true);
                }
                return $this->respondWithData([$avatar, $avatarNew]);
            } else {
                return $this->respondWithData('Unauthorzied', 401);
            }
            
        } catch(Exception $e) {
            return $this->respondWithData($e->getMessage(), 500);
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}