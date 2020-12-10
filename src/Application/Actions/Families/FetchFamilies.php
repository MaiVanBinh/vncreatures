<?php

namespace App\Application\Actions\Families;

use App\Application\Actions\Families\FamiliesAction;
use Exception;

class FetchFamilies extends FamiliesAction {
    public function action() {
        try{
            $query = $this->request->getQueryParams();
            $entires = array_key_exists('entires', $query) ? intval($query['entires']) : null;
            $page = array_key_exists('page', $query) ? intval($query['page']) : null;
            $families = $this->familiesServices->fetchFamilies($entires, $page);
            return $this->respondWithData($families);
        } catch(Exception $e) {
            $this->logger->warning('Ho list error');
            throw new Exception($e->getMessage());
        }
    }
}