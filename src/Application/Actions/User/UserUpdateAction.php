<?php

namespace App\Application\Actions\User;

use App\Application\Actions\User\UserAction;
use Exception;
use Slim\Exception\HttpNotFoundException;

final class UserUpdateAction extends UserAction{
    
    /**
     * {@inheritdoc}
     */
    protected function action() {
        try {
            $id = (int) $this->resolveArg('id');
            $data = (array) $this->request->getParsedBody();
            $data['id'] = $id;
            $userId = $this->userServices->updateUser($data);
            $result = ['id' => $userId];
            $this->logger->info('User updated', $result);
            return $this->respondWithData($result, 200);
        } catch(Exception $e) {
            $this->logger->warning('User update not found');
            throw new HttpNotFoundException($this->request, $e->getMessage());
        }
    }
}