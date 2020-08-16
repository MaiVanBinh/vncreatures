<?php

namespace App\Application\Actions\User;
use App\Application\Actions\User\UserAction;

class UserListAction extends UserAction{
    /**
     * {@inheritdoc}
     */
    protected function action() {
        $users = $this->userServices->listUser();
        $this->logger->info('User list');
        return $this->respondWithData($users);
    }
}