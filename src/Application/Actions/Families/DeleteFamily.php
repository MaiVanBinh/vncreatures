<?php

namespace App\Application\Actions\Families;
use App\Application\Actions\Families\FamiliesAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class DeleteFamily extends FamiliesAction {
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
                $isCreaturesInFamily = $this->creaturesService->countByFamily($id);
                if($isCreaturesInFamily == 0) {
                    $this->familiesServices->delete($id);
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