<?php

namespace App\Application\Actions\Creatures;

use Psr\Log\LoggerInterface;

use App\Application\Actions\Actions;
use App\Domain\Creatures\CreaturesServices;
use App\Domain\Assets\AssetsServices;
use App\Domain\User\UserServices;
use App\Domain\AssetsCreaturesServices;
use App\Application\Actions\Validator;
use Exception;

abstract class CreaturesActions extends Actions
{
    protected $validator;
    protected $creaturesServices;
    protected $assetsServices;
    protected $userServices;
    protected $acServices;
    public function __construct(
        LoggerInterface $logger, 
        CreaturesServices $creaturesServices, 
        AssetsServices $assetsServices, 
        UserServices $userServices,
        AssetsCreaturesServices $acServices,
        Validator $validator
        )
    {
        parent::__construct($logger);
        $this->validator = $validator;
        $this->creaturesServices = $creaturesServices;
        $this->assetsServices = $assetsServices;
        $this->userServices = $userServices;
        $this->acServices = $acServices;
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


    public function checkImageExistById($id)
    {
        $images = $this->assetsServices->fetchAssetById($id);
        if (count($images) > 0) {
            return $images[0]['url'];
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
