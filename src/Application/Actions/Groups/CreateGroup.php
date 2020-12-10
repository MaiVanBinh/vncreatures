<?php
namespace App\Application\Actions\Groups;

use App\Application\Actions\Groups\GroupsAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;

class CreateGroup extends GroupsAction {
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
                $id = $this->groupsServices->create($name_vn, $name_latin, $species, $token['id']);
                $createdGroup = $this->groupsServices->fetchGroupsById($id);
                return $this->respondWithData($createdGroup[0]);
            } else {
                return $this->respondWithData('Unauthorzied', 401);
            }
            
        } catch(Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}