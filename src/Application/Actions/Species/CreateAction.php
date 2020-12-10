<?php
namespace App\Application\Actions\Species;

use App\Application\Actions\Species\SpeciesAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Slim\Exception\HttpUnauthorizedException;

class CreateAction extends SpeciesAction {
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
                    "name_en"=>v::notEmpty()
                ]);
                if($this->validator->failed())
                {
                    $responseMessage = $this->validator->errors;
                    return $this->respondWithData($responseMessage, 404);
                }
                $name_vn = CustomRequestHandler::getParam($this->request, "name_vn");
                $name_en = CustomRequestHandler::getParam($this->request, "name_en");
                $id = $this->speciesService->create($name_vn, $name_en, $token['id']);
                // return $this->respondWithData($id);
                $createdSpecies = $this->speciesService->fetchSpeciesById($id);
                return $this->respondWithData($createdSpecies[0]);
            } else {
                return $this->respondWithData('Unauthorzied', 401);
            }
            
        } catch(Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}