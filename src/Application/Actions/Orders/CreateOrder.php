<?php
namespace App\Application\Actions\Orders;

use App\Application\Actions\Orders\OrdersActions;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class CreateOrder extends OrdersActions {
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
                    'group'=>v::digit()
                ]);
                if($this->validator->failed())
                {
                    $responseMessage = $this->validator->errors;
                    return $this->respondWithData($responseMessage, 404);
                }
                $name_vn = CustomRequestHandler::getParam($this->request, "name_vn");
                $name_latin = CustomRequestHandler::getParam($this->request, "name_latin");
                $group = CustomRequestHandler::getParam($this->request, "group");
                $id = $this->orderServices->create($name_vn, $name_latin, $group, $token['id']);
                $createdOrder = $this->orderServices->fetchOrderById($id);
                return $this->respondWithData($createdOrder[0]);
            } else {
                return $this->respondWithData('Unauthorzied', 401);
            }
            
        } catch(Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}