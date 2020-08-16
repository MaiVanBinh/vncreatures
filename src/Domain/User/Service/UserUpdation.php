<?php

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UserUpdateRepository;

class UserUpdation {
    private $repository;
    
    public function __construct(UserUpdateRepository $repository)
    {
        $this->repository = $repository;
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
     * Input validation.
     *
     * @param array $data The form data
     *
     * @throws ValidationException
     *
     * @return void
     */
    // private function validateNewUser(array $data): void
    // {
    //     $errors = [];

    //     // Here you can also use your preferred validation library

    //     if (empty($data['username'])) {
    //         $errors['username'] = 'Input required';
    //     }

    //     if (empty($data['email'])) {
    //         $errors['email'] = 'Input required';
    //     } elseif (filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false) {
    //         $errors['email'] = 'Invalid email address';
    //     }

    //     if ($errors) {
    //         throw new ValidationException('Please check your input' . $errors['username'], $errors);
    //     }
    // }
}