<?php

namespace App\Application\Actions\NationalParks;
use App\Application\Actions\NationalParks\NationalParksActions;
use Exception;
use Slim\Exception\HttpNotFoundException;

class FetchNationalParkById extends NationalParksActions {
    public function action() {
        try{
            $id = $this->resolveArg('id');
            $np = $this->npServices->fetchNationalParkById($id);

            if(count($np) == 0) {
                throw new Exception('Not Found');
            }
            return $this->respondWithData($np[0]);
        } catch(Exception $err) {
            throw new HttpNotFoundException($this->request, $err->getMessage());
        }
    }
}