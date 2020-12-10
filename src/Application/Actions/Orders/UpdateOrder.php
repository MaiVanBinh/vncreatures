<?php
namespace App\Application\Actions\Orders;
use App\Application\Actions\Orders\OrdersActions;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;

class UpdateOrder extends OrdersActions{
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
                $id = $this->resolveArg('id');
                if(!is_numeric($id)){
                    return $this->respondWithData('id not valid', 404);
                }
                
                $this->orderServices->update($id, $name_vn, $name_latin, $group, $token['id']);
                
                $updateOrder = $this->orderServices->fetchOrderById($id);
                if(count($updateOrder) > 0) {
                    return $this->respondWithData($updateOrder[0]);
                } else {
                    return $this->respondWithData('Orders not found', 404);
                }
            }
        } catch(Exception $ex) {
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }
}