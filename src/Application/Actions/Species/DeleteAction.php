<?php

namespace App\Application\Actions\Species;

use App\Application\Actions\Species\SpeciesAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;
use App\Requests\CustomRequestHandler;

class DeleteAction extends SpeciesAction {
    public function action() {
        try {
            $token = $this->request->getAttribute('token');
            $isUserExist = false;
            if($token) {
                $isUserExist = $this->checkUserExist($token['id']);
            }
            if($isUserExist) {
                $id = $this->resolveArg('id');
                if(!is_numeric($id)){
                    return $this->respondWithData('id not valid', 404);
                }
                $isGroupsInSpecies = $this->groupServices->countBySpecies($id);
                if($isGroupsInSpecies == 0) {
                    $this->speciesService->delete($id);
                    return $this->respondWithData('delete success', 200);
                } else {
                    return $this->respondWithData('delete not success', 404);
                }
            } else {
                return $this->respondWithData('Unauthorzied', 401);
            }
            
        } catch(Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}