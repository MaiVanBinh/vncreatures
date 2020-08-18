<?php

namespace App\Application\Actions\Creatures;

use App\Application\Actions\Creatures\CreaturesActions;
use Exception;

class CreaturesListAction extends CreaturesActions {
    public function action() {
        try{
            $filter = $this->request->getQueryParams();
            $creatures = $this->services->getCreaturesByFilter($filter);
            $creatures['length'] = count($creatures);
            return $this->respondWithData($creatures);
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}