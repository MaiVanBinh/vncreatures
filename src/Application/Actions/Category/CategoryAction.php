<?php

namespace App\Application\Actions\Category;

use App\Application\Actions\Actions;
use App\Domain\Category\CategoryServices;
use App\Domain\User\UserServices;
use Exception;
use Psr\Log\LoggerInterface;
use App\Application\Actions\Validator;
use App\Domain\Posts;
use App\Domain\Posts\PostsServices;

abstract class CategoryAction extends Actions {
    protected $categoryServices;
    protected $userServices;
    protected $validator;
    protected $postServices;
    public function __construct(CategoryServices $categoryServices, LoggerInterface $logger, UserServices $userServices, Validator $validator, PostsServices $postServices)
    {
        parent::__construct($logger);
        $this->categoryServices = $categoryServices;
        $this->userServices = $userServices;
        $this->validator = $validator;
        $this->postServices = $postServices;
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

    public function checkCateHasPosts($cateId) {
        $posts = $this->postServices->fetchListIdPostsOfCategory($cateId);
        if(count($posts) > 0) {
            return $posts;
        };
        return false;
    }
}