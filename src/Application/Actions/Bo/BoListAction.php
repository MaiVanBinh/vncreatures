<?php

namespace App\Application\Actions\Bo;

use App\Application\Actions\Bo\BoAction;
use Error;
use Exception;

class BoListAction extends BoAction {
    public function action() {
        try{
            $query = $this->request->getQueryParams();
            $nhomId = null;
            if(array_key_exists('nhomId', $query)) {
                $nhomId = $query['nhomId'];
            }
            $bo = $this->services->listBo($nhomId);
            $bo['length'] = count($bo);
            return $this->respondWithData($bo);
        } catch(Exception $e) {
            throw new Error("Bo list error");
        }
        
    }
}