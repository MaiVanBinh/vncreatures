<?php

namespace App\Application\Actions\Creatures;

use App\Application\Actions\Creatures\CreaturesActions;


use Exception;

class CreaturesListByFilterAction extends CreaturesActions
{
    public function action()
    {
        try {
            $filter = $this->request->getQueryParams();
            $creatures = $this->creaturesServices->getCreaturesByFilter($filter);
            $query = $this->request->getQueryParams();
            $limit = array_key_exists('limit', $filter) ? intval($filter['limit']) : 10;
            $page = array_key_exists('page', $filter) ? intval($filter['page']) : null;
            $total = $creatures['total'];
            
            $maxPage = ceil($total / $limit);
            $hasPrev = $page == 1 || $page - 1 > $maxPage ? false : true;
            $hasNext = $page >= $maxPage ? false : true;
            $creatures['pages'] = ['total' => ceil($total / $limit), 'current' => (int)$page, 'prev' => $page - 1, 'next' => $page + 1, 'hasPrev' => $hasPrev, 'hasNext' => $hasNext];
            return $this->respondWithData($creatures);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}