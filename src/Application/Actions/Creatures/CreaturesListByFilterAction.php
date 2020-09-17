<?php

namespace App\Application\Actions\Creatures;

use App\Application\Actions\Creatures\CreaturesActions;


use Exception;

class CreaturesListByFilterAction extends CreaturesActions {
    public function action() {
        try{
            $filter = $this->request->getQueryParams();
            $creatures = $this->creaturesServices->getCreaturesByFilter($filter);
            $data = [];
            $data['creatures'] = $creatures['creatures'];
            $data['total'] = $creatures['total'];
            $data['name'] = $creatures['name'];
            return $this->respondWithData($data);
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}