<?php

namespace App\Domain\User\Service;
use App\Domain\User\Repository\UserDeletionRepository;


class UserDeletion {
    private $repository;

    public function __construct(UserDeletionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Delete user by id
     * 
     * @param int id The user id
     * 
     * @return int id The user id is delete
     */
    public function deleteUserById($id) {
        $id = $this->repository->deleteUser($id);
        return $id;
    }
} 
