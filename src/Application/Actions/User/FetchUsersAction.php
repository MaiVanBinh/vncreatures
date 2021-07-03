<?php

namespace App\Application\Actions\User;

use App\Application\Actions\User\UserAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class FetchUsersAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action()
    {
        try {
            $token = $this->request->getAttribute('token');
            $userValid = $this->checkUserIsSuperAdmin($token['id']);
            if (!$userValid) {
                return $this->respondWithData("Not Authorized", 401);
            } else {
                $filter = $this->request->getQueryParams();
                $page = array_key_exists('page', $filter) ? intval($filter['page']) : 1;
                $limit = array_key_exists('limit', $filter) ? intval($filter['limit']) : 10;
                $name = array_key_exists('username', $filter) ? $filter['username'] : '';
                $this->logger->info('User list');
                $users = $this->userServices->fetchUser($page, $limit, $name);
                
                 // get page
                $total = (int)$users['total'];
                $maxPage = ceil($total / $limit);
                $hasPrev = $page == 1 || $page - 1 > $maxPage ? false : true;
                $hasNext = $page >= $maxPage ? false : true;
                $users['pages'] = ['total' => ceil($total/$limit), 'current' => $page, 'prev' => $page - 1, 'next' => $page + 1, 'hasPrev' => $hasPrev, 'hasNext' => $hasNext];
                return $this->respondWithData($users, 200);
            }
            return $this->respondWithData($userValid);
        } catch (Exception $ex) {
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }
}
