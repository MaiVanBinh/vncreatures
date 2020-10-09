<?php

namespace App\Application\Actions\NationalParks;
use App\Application\Actions\NationalParks\NationalParksActions;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class FetchNationParks extends NationalParksActions {
    public function action() {
        try {
            $nationalParks = $this->npServices->fetchNationParks();
            return $this->respondWithData($nationalParks);
        } catch(Exception $err) {
            throw new HttpInternalServerErrorException($this->request,  $err->getMessage());
        }
    }
        
}