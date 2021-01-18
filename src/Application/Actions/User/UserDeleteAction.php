<?php

namespace App\Application\Actions\User;

use App\Application\Actions\User\UserAction;
use Exception;

class UserDeleteAction extends UserAction{
    /**
     * {@inheritdoc}
     */
    protected function action() {
        try {
            $token = $this->request->getAttribute('token');
            $valid = $this->userServices->checkUserIsSuperAdmin($token['id']);
            if(!$valid) {
                return $this->respondWithData('Not Auth', 401);
            } 
            $id = $this->resolveArg('id');
            $this->userServices->deleteUser($id);
            return $this->respondWithData('Delete User', 200);
        } catch(Exception $e) {
            $this->logger->warning('User detele not success');
            throw new Exception($e->getMessage());
        }
    }
}