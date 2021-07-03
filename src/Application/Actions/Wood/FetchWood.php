<?php

namespace App\Application\Actions\Wood;

use App\Application\Actions\Wood\WoodAction;

class FetchWood extends WoodAction {
    public function action() {
        $filter = $this->request->getQueryParams();
        $page = array_key_exists('page', $filter) ? intval($filter['page']) : 1;
        $limit = array_key_exists('limit', $filter) ? intval($filter['limit']) : 40;
        $name = array_key_exists('name', $filter) ? $filter['name'] : '';
        $woods = $this->woodServices->fetchWoodForm($limit, $page, $name);
        $total = (int)$woods['total'];
        $maxPage = ceil($total / $limit);
        $hasPrev = $page == 1 || $page - 1 > $maxPage ? false : true;
        $hasNext = $page >= $maxPage ? false : true;
        $woods['pages'] = ['total' => $maxPage, 'current' => $page, 'prev' => $page - 1, 'next' => $page + 1, 'hasPrev' => $hasPrev, 'hasNext' => $hasNext];
        return $this->respondWithData($woods);
    }
}