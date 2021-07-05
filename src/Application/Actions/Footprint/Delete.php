<?php
namespace App\Application\Actions\Footprint;

use App\Application\Actions\Footprint\FootprintAction;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class Delete extends FootprintAction {
    public function action() {
        try {
            $token = $this->request->getAttribute('token');
            $isUserExist = false;
            if($token) {
                $isUserExist = $this->checkUserExist($token['id']);
            }
            if($isUserExist) {
                $id =$this->resolveArg('id');
                $footprint = $this->footprintServices->fetchFootprintById($id);
                if (count($footprint) === 0) {
                    return $this->respondWithData("Footprint not found", 400);
                }
                $avatar = $footprint[0]['avatar'];
                $this->footprintServices->delete($id);
                $this->unLinkImage($avatar);
                return $this->respondWithData('Delete Success', 200);
            } else {
                return $this->respondWithData('Unauthorzied', 401);
            }
            
        } catch(Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}