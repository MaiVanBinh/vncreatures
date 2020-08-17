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
            $id = $this->resolveArg('id');
            $user = $this->userServices->findUserById($id);
            if($user) {
                unlink( __DIR__ . '/../../../../assets/images/' . $user['imageUrl']);
            } else {
                throw new Exception('User Not Found');
            }
            
            $id = $this->userServices->deleteUserById($id);
            $result = ['id' => $id];
            
            $this->logger->info('Delete User', $result);
            
            return $this->respondWithData($result, 200);
        } catch(Exception $e) {
            $this->logger->warning('User detele not success');
            throw new Exception($e->getMessage());
        }
    }
}