<?php

namespace App\Application\Actions\User;

use App\Application\Actions\User\UserAction;

class FetchCreaturesAdmin extends UserAction {
    public function action() {
        $filter = $this->request->getQueryParams();
        $limit = array_key_exists('limit', $filter) ? intval($filter['limit']) : 30;
        $name_vn = null;
        if(array_key_exists('name_vn', $filter) && $filter['name_vn'] !== '') {
            
        }
    }
}