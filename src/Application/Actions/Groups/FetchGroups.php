<?php

namespace App\Application\Actions\Groups;
use App\Application\Actions\Groups\GroupsAction;
use App\Application\Actions\Posts\FetchPosts;
use Exception;

class FetchGroups extends GroupsAction {
    public function action() {
        try{
            $filter = $this->request->getQueryParams();
            $entires = array_key_exists('entires', $filter) ? intval($filter['entires']) : null;
            $page = array_key_exists('page', $filter) ? intval($filter['page']) : null;
            $filter = $this->getFilterNameAndValue($filter);
            // if(array_key_exists('id', $filter)) {

            // }
            $groups = $this->groupsServices->fetchGroup($entires, $page,null, $filter);
            $this->logger->info('Find Groups');
            array_push($groups, count($groups));
            return $this->respondWithData($groups);
        } catch(Exception $e) {
            $this->logger->warning('Groups list by Species id error');
            throw new Exception($e->getMessage());
        }
    }
} 