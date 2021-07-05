<?php

namespace App\Application\Actions\Posts;

use App\Application\Actions\Actions;
use Psr\Log\LoggerInterface;
use App\Domain\Posts\PostsServices;
use App\Application\Actions\Validator;
use App\Domain\User\UserServices;
use Exception;
use App\Domain\Assets\AssetsServices;
use App\Domain\AssetsPostServices;
use App\Domain\Category\CategoryServices;

abstract class PostsActions extends Actions
{
    protected $postsServices;
    protected $validator;
    protected $userServices;
    protected $assetsServices;
    protected $PIServices;
    protected $categoryServices;

    public function __construct(CategoryServices $categoryServices, AssetsPostServices $PIServices, PostsServices $service, Validator $validator, LoggerInterface $logger, UserServices $userServices, AssetsServices $assetsServices)
    {
        parent::__construct($logger);
        $this->postsServices = $service;
        $this->userServices = $userServices;
        $this->validator = $validator;
        $this->assetsServices = $assetsServices;
        $this->PIServices = $PIServices;
        $this->categoryServices = $categoryServices;
    }

    public function checkUserExist($id)
    {
        try {
            $user = $this->userServices->findUserById($id);
            if (count($user) > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            throw $ex->getMessage();
        }
    }

    // public function checkImageExistByUrl($url)
    // {
    //     $images = $this->assetsServices->findImageByUrl($url);
    //     if (count($images) > 0) {
    //         return $images[0]['id'];
    //     }
    //     return false;
    // }


    public function checkImageExistById($id)
    {
        $images = $this->assetsServices->fetchAssetById($id);
        if (count($images) > 0) {
            return true;
        }
        return false;
    }
    public function categoryExist($id)
    {
        $category = $this->categoryServices->fetchCategoryById($id);
        if ($category) {
            return true;
        }
        return false;
    }

    public function unLinkImageWithPost($imageId, $postId)
    {
        $this->assetsServices->unlinkBaseOnImageAndPost($imageId, $postId);
        $isImageUse = $this->assetsServices->checkAssetInUse($imageId);
        if (!$isImageUse) {
            $this->assetsServices->useImage($imageId, false);
        }
    }
}