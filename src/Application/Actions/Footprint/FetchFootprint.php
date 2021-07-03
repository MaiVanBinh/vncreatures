<?php

namespace App\Application\Actions\Footprint;

use App\Application\Actions\Footprint\FootprintAction;
use Exception;

class FetchFootprint extends FootprintAction {
    public function action() {
        try {
            $filter = $this->request->getQueryParams();
            $page = array_key_exists('page', $filter) ? intval($filter['page']) : 1;
            $limit = array_key_exists('limit', $filter) ? intval($filter['limit']) : 10;
            $name = array_key_exists('name', $filter) ? $filter['name'] : '';
            $footprint = $this->footprintServices->fetchFootprint($limit, $page, $name);
            $total = (int)$footprint['total'];
            $maxPage = ceil($total / $limit);
            $hasPrev = $page == 1 || $page - 1 > $maxPage ? false : true;
            $hasNext = $page >= $maxPage ? false : true;
            $footprint['pages'] = ['total' => $maxPage, 'current' => $page, 'prev' => $page - 1, 'next' => $page + 1, 'hasPrev' => $hasPrev, 'hasNext' => $hasNext];
            return $this->respondWithData($footprint);
        } catch(Exception $ex ) {
            return $this->respondWithData($ex->getMessage());
            throw new Exception($e->getMessage());
        }
        
    }
}