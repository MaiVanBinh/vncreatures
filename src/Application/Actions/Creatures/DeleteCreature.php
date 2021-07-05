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
            $oldImages = $this->assetsServices->fetchCreatureImage($creatureId);
            $len = count($oldImages);

            if ($len > 0) {
                for ($i = 0; $i < $len; $i++) {
                    $this->unLinkImageWithCreatures($oldImages[$i]['id'], $creatureId);
                }
            }
            $this->creaturesServices->deleteCreature($creatureId);
            return $this->respondWithData('Delete success', 200);
        } catch (Exception $ex) {
            return $this->respondWithData($ex->getMessage(), 400);
        }
    }
}
