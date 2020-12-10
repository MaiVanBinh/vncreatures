<?php

namespace App\Application\Actions\Groups;
use App\Application\Actions\Groups\GroupsAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;

class UpdateGroup extends GroupsAction {
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
                    'species'=>v::digit()
                ]);
                if($this->validator->failed())
                {
                    $responseMessage = $this->validator->errors;
                    return $this->respondWithData($responseMessage, 404);
                }
                $name_vn = CustomRequestHandler::getParam($this->request, "name_vn");
                $name_latin = CustomRequestHandler::getParam($this->request, "name_latin");
                $species = CustomRequestHandler::getParam($this->request, "species");
                $id = $this->resolveArg('id');
                if(!is_numeric($id)){
                    return $this->respondWithData('id not valid', 404);
                }
                $this->groupsServices->update($id, $name_vn, $name_latin, $species,$token['id']);
                $updateGroup = $this->groupsServices->fetchGroupsById($id);
                if(count($updateGroup) > 0) {
                    return $this->respondWithData($updateGroup[0]);
                } else {
                    return $this->respondWithData('Group not found', 404);
                }
            }
        } catch(Exception $ex) {
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }
}