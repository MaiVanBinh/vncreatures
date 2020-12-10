<?php

namespace App\Application\Actions\Families;
use App\Application\Actions\Families\FamiliesAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;

class UpdateFamily extends FamiliesAction {
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
                    'order'=>v::digit()
                ]);
                if($this->validator->failed())
                {
                    $responseMessage = $this->validator->errors;
                    return $this->respondWithData($responseMessage, 404);
                }
                $name_vn = CustomRequestHandler::getParam($this->request, "name_vn");
                $name_latin = CustomRequestHandler::getParam($this->request, "name_latin");
                $order = CustomRequestHandler::getParam($this->request, "order");
                $id = $this->resolveArg('id');
                if(!is_numeric($id)){
                    return $this->respondWithData('id not valid', 404);
                }
                $this->familiesServices->update($id, $name_vn, $name_latin, $order,$token['id']);
                $updateFamily = $this->familiesServices->fetchFamilyById($id);
                if(count($updateFamily) > 0) {
                    return $this->respondWithData($updateFamily[0]);
                } else {
                    return $this->respondWithData('Family not found', 404);
                }
            }
        } catch(Exception $ex) {
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }
}