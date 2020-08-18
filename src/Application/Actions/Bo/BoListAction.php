<?php

namespace App\Application\Actions\Bo;

use App\Application\Actions\Bo\BoActions;
use Exception;

class BoListAction extends BoActions {
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
            throw new Exception("Bo list error");
        }
        
    }
}