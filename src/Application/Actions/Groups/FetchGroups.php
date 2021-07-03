<?php

namespace App\Application\Actions\Groups;
use App\Application\Actions\Groups\GroupsAction;
use App\Application\Actions\Posts\FetchPosts;
use Exception;

class FetchGroups extends GroupsAction {
    public function action() {
        try{
            $filter = $this->request->getQueryParams();
            $limit = array_key_exists('limit', $filter) ? intval($filter['limit']) : 10;
            $page = array_key_exists('page', $filter) ? intval($filter['page']) : 1;
            $name = array_key_exists('name', $filter) ? $filter['name'] : '';
            $species = array_key_exists('species', $filter) ? intval($filter['species']) : null;

            $groups = $this->groupsServices->fetchGroup($limit, $page,null, $name, $species);

             // get page
             $total = $groups['total'];
             $maxPage = ceil($total / $limit);
             $hasPrev = $page == 1 || $page - 1 > $maxPage ? false : true;
             $hasNext = $page >= $maxPage ? false : true;
             $groups['pages'] = ['total' => $maxPage, 'current' => (int)$page, 'prev' => $page - 1, 'next' => $page + 1, 'hasPrev' => $hasPrev, 'hasNext' => $hasNext];
            return $this->respondWithData($groups);
        } catch(Exception $e) {
            $this->logger->warning('Groups list by Species id error');
            throw new Exception($e->getMessage());
        }
    }
}