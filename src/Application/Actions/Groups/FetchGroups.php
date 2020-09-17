<?php

namespace App\Application\Actions\Groups;
use App\Application\Actions\Groups\GroupsAction;
use Exception;

class FetchGroups extends GroupsAction {
    public function action() {
        try{
            $groups = $this->services->fetchGroup();
            $this->logger->info('Find Groups');
            return $this->respondWithData($groups);
        } catch(Exception $e) {
            $this->logger->warning('Groups list by Species id error');
            throw new Exception($e->getMessage());
        }
    }
} 