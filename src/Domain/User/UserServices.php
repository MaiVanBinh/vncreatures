<?php

namespace App\Domain\User;

use App\Domain\User\UserRepository;
use App\Exception\ValidationException;

/**
 * Service.
 */
final class UserServices
{
    /**
     * @var UserCreatorRepository
     */
    private $repository;

    /**
     * The constructor.
     *
     * @param UserCreatorRepository $repository The repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Create a new user.
     *
     * @param array $data The form data
     *
     * @return int The new user ID
     */
    public function createUser(array $data): int
    {
        // Input validation
        // $this->validateNewUser($data);

        // Insert user
        $userId = $this->repository->insertUser($data);

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $userId;
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

    /**
     * @return array the list of user to find
     */
    public function listUser() {
        $users = $this->repository->listUser();
        return $users;
    }

    /**
     * Update user by id
     * 
     * @param array data The information of user
     * 
     * @return int id The id of user was updated
     */
    public function updateUser($data) {
        // $this->validateNewUser($data)
        $id = $this->repository->updateUser($data);
        return $id;
    } 

    /**
     * find user by id
     * 
     * @param int id The information of user
     * 
     * @return array data The data of user
     */
    public function findUserById($id) {
        $user = $this->repository->findUserById($id);
        return $user;
    } 

    /**
     * Input validation.
     *
     * @param array $data The form data
     *
     * @throws ValidationException
     *
     * @return void
     */
    private function validateNewUser(array $data): void
    {
        $errors = [];

        // Here you can also use your preferred validation library

        if (empty($data['username'])) {
            $errors['username'] = 'Input required';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Input required';
        } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'Invalid email address';
        }

        if ($errors) {
            throw new ValidationException('Please check your input' . $errors['username'], $errors);
        }
    }
}