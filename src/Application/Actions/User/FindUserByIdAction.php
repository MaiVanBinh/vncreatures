<?php

namespace App\Application\Actions\User;

use Slim\Exception\HttpNotFoundException;

use App\Application\Actions\User\UserAction;
use Exception;



class FindUserByIdAction extends UserAction {
    /**
     * {@inheritdoc}
     */
    protected function action() {
        try{
            $id = $this->resolveArg('id');
            $user = $this->userServices->findUserById($id);
            $this->logger->info('Find user ', $user);
            return $this->respondWithData($user);
        } catch(Exception $e) {
            $this->logger->warning('User not found');
            throw new HttpNotFoundException($this->request, $e->getMessage());
        }
    }
}