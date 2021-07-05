<?php
namespace App\Application\Actions\Wood;

use App\Application\Actions\Wood\WoodAction;

use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class Delete extends WoodAction {
    public function action() {
        try {
            $token = $this->request->getAttribute('token');
            $isUserExist = false;
            if($token) {
                $isUserExist = $this->checkUserExist($token['id']);
            }
            if($isUserExist) {
                $id =$this->resolveArg('id');
                $wood = $this->woodServices->fetchWoodprintById($id);
                if (count($wood) === 0) {
                    return $this->respondWithData("Wood not found", 400);
                }
                $img = $wood[0]['img'];
                $this->woodServices->delete($id);
                $this->unLinkImage($img);
                return $this->respondWithData('Delete Success', 200);
            } else {
                return $this->respondWithData('Unauthorzied', 401);
            }
            
        } catch(Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}