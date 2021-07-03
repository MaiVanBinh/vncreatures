<?php

namespace App\Application\Actions\Orders;

use App\Application\Actions\Orders\OrdersActions;
use Exception;

class FetchOrders extends OrdersActions {
    public function action() {
        try{
            $filter = $this->request->getQueryParams();
            $limit = array_key_exists('limit', $filter) ? intval($filter['limit']) : 10;
            $page = array_key_exists('page', $filter) ? intval($filter['page']) : 1;
            $name = array_key_exists('name', $filter) ? $filter['name'] : '';
            $group = array_key_exists('group', $filter) ? intval($filter['group']) : null;
            
            $orders = $this->orderServices->fetchOrder($limit, $page, null, $name, $group);
            $total = $orders['total'];
             $maxPage = ceil($total / $limit);
             $hasPrev = $page == 1 || $page - 1 > $maxPage ? false : true;
             $hasNext = $page >= $maxPage ? false : true;
             $orders['pages'] = ['total' => $maxPage, 'current' => (int)$page, 'prev' => $page - 1, 'next' => $page + 1, 'hasPrev' => $hasPrev, 'hasNext' => $hasNext];
            return $this->respondWithData($orders);
        } catch(Exception $e) {
            return $this->respondWithData($e->getMessage(), 500);
        }
    }
}
