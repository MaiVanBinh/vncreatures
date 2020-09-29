<?php 

namespace App\Application\Actions\Creatures;
use App\Application\Actions\Creatures\CreaturesActions;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class CreaturesRedBook extends CreaturesActions {
    public function action() {
        try{
            $filter = $this->request->getQueryParams();
            $creatures = $this->creaturesServices->fetchCreatureRedBook($filter);
            return $this->respondWithData($creatures);
        }  catch(Exception $err) {
            throw new HttpInternalServerErrorException($this->request, $err->getMessage());
        }
    }
}