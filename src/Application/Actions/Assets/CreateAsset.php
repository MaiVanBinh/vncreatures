<?php

namespace App\Application\Actions\Assets;

use App\Application\Actions\Assets\AssetsAction;
use Exception;
use Psr\Http\Message\UploadedFileInterface;

class CreateAsset extends AssetsAction
{
    public function action()
    {
        try {
            $token = $this->request->getAttribute('token');

            //upload file
            $directory = __DIR__ . '/../../../../assets/images';

            // Get all file upload
            $uploadedFiles = $this->request->getUploadedFiles();

            $uploadedFile = $uploadedFiles['image'];
            $size = $uploadedFile->getSize();
            $name = $uploadedFile->getClientFilename();
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $filename = $this->moveUploadedFile($directory, $uploadedFile, $name);
                
                $uri = $this->request->getUri();
                $imageUrl = $uri->getScheme() . '://' . $uri->getHost();
                
                if ($uri->getHost() === 'localhost') {
                   $imageUrl .= ':' . $uri->getPort();
                }
                $imageUrl .= '/vnback/assets/' . explode(".", $filename)[0];
                $id = $this->assetsServices->createAsset($imageUrl, $filename, $size, $token['id']);
                $asset = $this->assetsServices->fetchAssetById($id);
                return $this->respondWithData($asset);
            }
            
        } catch (Exception $ex) {
            return $this->respondWithData($ex->getMessage(), 200);
        }
    }
    public function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile, string $title)
    {
        $filename = strtotime("now") ."_". $title;
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}