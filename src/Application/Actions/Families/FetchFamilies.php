<?php

namespace App\Application\Actions\Families;

use App\Application\Actions\Families\FamiliesAction;
use Exception;

class FetchFamilies extends FamiliesAction
{
    public function action()
    {
        try {
            $filter = $this->request->getQueryParams();
            $limit = array_key_exists('limit', $filter) ? intval($filter['limit']) : 10;
            $page = array_key_exists('page', $filter) ? intval($filter['page']) : 1;
            $name = array_key_exists('name', $filter) ? $filter['name'] : '';
            $order= array_key_exists('order', $filter) ? intval($filter['order']) : null;
            
            $families = $this->familiesServices->fetchFamilies($limit, $page, null, $name, $order);
            $total = $families['total'];
            $maxPage = ceil($total / $limit);
            $hasPrev = $page == 1 || $page - 1 > $maxPage ? false : true;
            $hasNext = $page >= $maxPage ? false : true;
            $families['pages'] = ['total' => $maxPage, 'current' => (int)$page, 'prev' => $page - 1, 'next' => $page + 1, 'hasPrev' => $hasPrev, 'hasNext' => $hasNext];
            return $this->respondWithData($families);
        } catch (Exception $e) {
            $this->logger->warning('Ho list error');
            throw new Exception($e->getMessage());
        }
    }
}