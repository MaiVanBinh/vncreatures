<?php

namespace App\Application\Actions\Groups;

use App\Application\Actions\Groups\GroupsAction;
use Exception;

class GroupsListAction extends GroupsAction {
    public function action() {
        try{
            $query = $this->request->getQueryParams();
            $loaiId = null;
            if(array_key_exists('loaiId', $query)) {
                $loaiId = $query['loaiId'];
            }
            $Groups = $this->services->getGroupsBySpecies($loaiId);
            $this->logger->info('Find Groups by Species id', ['loaiId' => $loaiId]);
            return $this->respondWithData($Groups);
        } catch(Exception $e) {
            $this->logger->warning('Groups list by Species id error');
            throw new Exception($e->getMessage());
        }
    }
}