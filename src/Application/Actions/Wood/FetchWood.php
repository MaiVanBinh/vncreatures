<?php

namespace App\Application\Actions\Wood;

use App\Application\Actions\Wood\WoodAction;

class FetchWood extends WoodAction {
    public function action() {
        $query = $this->request->getQueryParams();
        $page = array_key_exists('page', $query) && $query['page'] ? $query['page'] : 1;
        $woods = $this->woodServices->fetchWoodForm($page);
        return $this->respondWithData(['wood' => $woods]);
    }
}