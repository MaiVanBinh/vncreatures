<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserListRepository;

class UserList {
    /**
     * @var UserCreatorRepository
     */
    private $repository;

    public function __construct(UserListRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * @return array the list of user to find
     */
    public function listUser() {
        $users = $this->repository->listUser();
        return $users;
    }
}