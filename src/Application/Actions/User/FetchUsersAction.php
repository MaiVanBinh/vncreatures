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
            $userValid = $this->checkUserExist($token['id']);
            if (!$userValid) {
                return $this->respondWithData("Not Authorized", 401);
            } else {
                $this->logger->info('User list');
                $users = $this->userServices->fetchUser();
                return $this->respondWithData($users);
            }
            return $this->respondWithData($userValid);
        } catch (Exception $ex) {
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }
}
