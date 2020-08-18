<?php

namespace App\Application\Actions\Ho;

use App\Application\Actions\Ho\HoAction;
use Exception;

class HoListAction extends HoAction {
    public function action() {
        try{
            $query = $this->request->getQueryParams();
            $boId = null;
            if(array_key_exists('boId', $query)) {
                $boId = $query['boId'];
            }
            $ho = $this->services->listBo($boId);
            $this->logger->info('Find Ho', ['boId' => $boId]);
            return $this->respondWithData($ho);
        } catch(Exception $e) {
            $this->logger->warning('Ho list error');
            throw new Exception($e->getMessage());
        }
    }
}