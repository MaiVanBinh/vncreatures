<?php

namespace App\Application\Actions\Family;

use App\Application\Actions\Ho\HoAction;
use Exception;

class FamilyListAction extends FamilyAction {
    public function action() {
        try{
            $query = $this->request->getQueryParams();
            $boId = null;
            if(array_key_exists('boId', $query)) {
                $boId = $query['boId'];
            }
            $ho = $this->services->listBo($boId);
            $ho['length'] = count($ho);
            $this->logger->info('Find Ho', ['boId' => $boId]);
            return $this->respondWithData($ho);
        } catch(Exception $e) {
            $this->logger->warning('Ho list error');
            throw new Exception($e->getMessage());
        }
    }
}