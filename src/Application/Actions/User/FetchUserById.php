<?php

namespace App\Application\Actions\User;

use Slim\Exception\HttpNotFoundException;

use App\Application\Actions\User\UserAction;
use Exception;
use PhpParser\Node\Stmt\Throw_;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpUnauthorizedException;

class FetchUserById extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action()
    {
        try {
            $id = $this->resolveArg('id');
            $token = $this->request->getAttribute("token");
            $email = $token['email'];
            $users = $this->userServices->findUserById($id);
            if (count($users) === 0) {
                return $this->respondWithData('USER NOT FOUND', 404);
            }
            if ($email  === $users[0]['email']) {
                return $this->respondWithData($users[0]);
            }
            throw new HttpUnauthorizedException($this->request, 'NOT AUTHORIZE');
        } catch (Exception $ex) {
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }
}
