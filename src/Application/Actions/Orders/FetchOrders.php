<?php

namespace App\Application\Actions\Orders;

use App\Application\Actions\Orders\OrdersActions;
use Exception;

class FetchOrders extends OrdersActions {
    public function action() {
        try{
            $query = $this->request->getQueryParams();
            $entires = array_key_exists('entires', $query) ? intval($query['entires']) : null;
            $page = array_key_exists('page', $query) ? intval($query['page']) : null;
            $name_vn =  array_key_exists('name_vn', $query) ? $query['name_vn'] : null;
            $orders = $this->orderServices->fetchOrder($entires, $page, null, $name_vn);
            return $this->respondWithData($orders);
        } catch(Exception $e) {
            throw new Exception("Bo list error");
        }
    }
}