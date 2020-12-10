<?php

namespace App\Application\Actions\Orders;
use App\Application\Actions\Orders\OrdersActions;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class DeleteOrder extends OrdersActions {
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
                $isfamiliesInOrder = $this->familiesServices->countByOrder($id);
                if($isfamiliesInOrder == 0) {
                    $this->orderServices->delete($id);
                    // return $this->respondWithData($sql, 200);
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