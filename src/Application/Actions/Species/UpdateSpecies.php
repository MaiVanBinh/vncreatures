<?php
namespace App\Application\Actions\Species;
use App\Application\Actions\Species\SpeciesAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;

class UpdateSpecies extends SpeciesAction {
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
                $id = $this->resolveArg('id');
                if(!is_numeric($id)){
                    return $this->respondWithData('id not valid', 404);
                }
                $this->speciesService->update($id, $name_vn, $name_en, $token['id']);
                $updateSpecies = $this->speciesService->fetchSpeciesById($id);
                if(count($updateSpecies) > 0) {
                    return $this->respondWithData($updateSpecies[0]);
                } else {
                    return $this->respondWithData('Species not found', 404);
                }
            }
        } catch(Exception $ex) {
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }
}