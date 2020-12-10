<?php

namespace App\Application\Actions\Posts;

use App\Application\Actions\Actions;
use Psr\Log\LoggerInterface;
use App\Domain\Posts\PostsServices;
use App\Application\Actions\Validator;
use App\Domain\User\UserServices;
use Exception;

abstract class PostsActions extends Actions {
    protected $postsServices;
    protected $validator;
    protected $userServices;

    public function __construct(PostsServices $service, Validator $validator, LoggerInterface $logger, UserServices $userServices)
    {
        parent::__construct($logger);
        $this->postsServices = $service;
        $this->userServices = $userServices;
        $this->validator = $validator;
    }

    public function checkUserExist($id) {
        try {
            $user = $this->userServices->findUserById($id);
            if(count($user) > 0) {
                return true;
            } else {
                return false;
            }
        } catch(Exception $ex) {
            throw $ex->getMessage();
        }
    }
}