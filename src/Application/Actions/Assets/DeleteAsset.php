<?php

namespace App\Application\Actions\Assets;

use App\Application\Actions\Assets\AssetsAction;
use Exception;
use Psr\Http\Message\UploadedFileInterface;

class DeleteAsset extends AssetsAction
{


    public function action()
    {
        try {
            $token = $this->request->getAttribute('token');
            $id =$this->resolveArg('id');
            $image = $this->assetsServices->fetchAssetById($id);
            $directory = __DIR__ . '/../../../../assets/images/';
            if(count($image) > 0 && $image[0]['in_use'] == '0') {
                unlink($directory . $image[0]['name']);
                $this->assetsServices->deleteAsset($id);
                return $this->respondWithData('Delete Success', 200);
            }
            return $this->respondWithData('Delete Not Success', 400);
        } catch (Exception $ex) {
            return $this->respondWithData($ex->getMessage(), 200);
        }
    }
    public function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile, string $title)
    {
        $filename = strtotime("now") . $title . '.png';
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return explode(".", $filename)[0];;
    }
}
