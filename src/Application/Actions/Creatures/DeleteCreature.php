<?php

namespace App\Application\Actions\Creatures;

use App\Application\Actions\Creatures\CreaturesActions;
use Exception;
use Psr\Http\Message\UploadedFileInterface;

class DeleteCreature extends CreaturesActions
{
    public function action()
    {
        try {
            $token = $this->request->getAttribute('token');
            if ($token) {
                if (!$this->checkUserExist($token['id'])) {
                    return $this->respondWithData('Unauthorzied', 401);
                };
            }
            $creatureId = $this->resolveArg('id');
            $this->assetsServices->unLinkAssetCretures($creatureId);
            $this->creaturesServices->deleteCreature($creatureId);
            return $this->respondWithData('Delete success', 200);
        } catch (Exception $ex) {
            return $this->respondWithData($ex->getMessage(), 400);
        }
    }
}
