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
            $creatureId = $this->resolveArg('id');
            $this->assetsServices->unLinkAssetCretures($creatureId);
            $this->creaturesServices->deleteCreature($creatureId);
            return $this->respondWithData('Delete success', 200);
        } catch (Exception $ex) {
            return $this->respondWithData($ex->getMessage(), 400);
        }
    }

    function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
    {
        $filename = strtotime("now") . explode('.', $uploadedFile->getClientFilename())[0] . '.png';
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return explode(".", $filename)[0];
    }
}
