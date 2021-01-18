<?php

namespace App\Application\Actions\Posts;

use App\Application\Actions\Actions;
use Psr\Log\LoggerInterface;
use App\Domain\Posts\PostsServices;
use App\Application\Actions\Validator;
use App\Domain\User\UserServices;
use Exception;
use App\Domain\Assets\AssetsServices;

abstract class PostsActions extends Actions {
    protected $postsServices;
    protected $validator;
    protected $userServices;
    protected $assetsServices;

    public function __construct(PostsServices $service, Validator $validator, LoggerInterface $logger, UserServices $userServices, AssetsServices $assetsServices)
    {
        parent::__construct($logger);
        $this->postsServices = $service;
        $this->userServices = $userServices;
        $this->validator = $validator;
        $this->assetsServices = $assetsServices;
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