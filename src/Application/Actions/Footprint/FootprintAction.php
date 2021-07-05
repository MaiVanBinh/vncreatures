<?php

namespace App\Application\Actions\Footprint;
use App\Application\Actions\Actions;
use Psr\Log\LoggerInterface;
use App\Domain\Footprint\FootprintServices;
use App\Domain\User\UserServices;
use Exception;
use App\Application\Actions\Validator;
use App\Domain\Assets\AssetsServices;
abstract class FootprintAction extends Actions {
    protected $footprintServices;
    protected $userServices;
    protected $validator;
    protected $assetsServices;

    public function __construct(AssetsServices $assetsServices, FootprintServices $footprintServices, LoggerInterface $logger, UserServices $userServices, Validator $validator) {
        parent::__construct($logger);
        $this->footprintServices = $footprintServices;
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
    public function unLinkImage($imageId)
    {
        $isImageUse = $this->assetsServices->checkAssetInUse($imageId);
        if (!$isImageUse) {
            $this->assetsServices->useImage($imageId, false);
        }
        return $isImageUse;
    }
}