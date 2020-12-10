<?php
namespace App\Application\Actions\Families;

use App\Application\Actions\Families\FamiliesAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;

class CreateFamily extends FamiliesAction {
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
                    "order"=>v::digit()
                ]);
                if($this->validator->failed())
                {
                    $responseMessage = $this->validator->errors;
                    return $this->respondWithData($responseMessage, 404);
                }
                $name_vn = CustomRequestHandler::getParam($this->request, "name_vn");
                $name_latin = CustomRequestHandler::getParam($this->request, "name_latin");
                $order = CustomRequestHandler::getParam($this->request, "order");
                $id = $this->familiesServices->create($name_vn, $name_latin, $order, $token['id']);
                $createdFamily = $this->familiesServices->fetchFamilyById($id);
                return $this->respondWithData($createdFamily[0]);
            } else {
                return $this->respondWithData('Unauthorzied', 401);
            }
            
        } catch(Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}