<?php

namespace App\Application\Actions\Creatures;

use App\Application\Actions\Creatures\CreaturesActions;
use Exception;
use Psr\Http\Message\UploadedFileInterface;

class CreatureEditAction extends CreaturesActions
{
    public function action()
    {
        $token = $this->request->getAttribute('token');
        $creatureId = $this->resolveArg('id');
        $creatureUpdate = $this->request->getParsedBody();
        
        //upload file
        $directory = __DIR__ . '/../../../../assets/images';

        // Get all file upload
        $uploadedFiles = $this->request->getUploadedFiles();
        
        $numberImage = (int)$creatureUpdate['numberImage'];
        for($i = 0; $i < $numberImage; $i++) {
            $uploadedFile = $uploadedFiles["image{$i}"];
            $filename = null;
            if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                $filename = $this->moveUploadedFile($directory, $uploadedFile);
                $uri = $this->request->getUri();
                $imageUrl = $uri->getScheme() . '://' . $uri->getHost() ;
                if($uri->getHost() === 'localhost') {
                    $imageUrl .= ':' . $uri->getPort();
                }
                $imageUrl .= "/assets/" . $filename;  
                $id = $this->assetsServices->createAsset($imageUrl, $filename);
                $this->acServices->createNewOne($id, (int)$creatureId);
            }
            if (!$filename) {
                throw new Exception('Upload image error');
            }
        }
        
        $this->acServices->deletedImages($creatureUpdate['imagesDeleted'], $creatureId);
        
        $this->creaturesServices->editCreatureById($creatureUpdate, $token['id']);

        $creatures = $this->creaturesServices->fetchCreatureById($creatureId);
        $images = $this->assetsServices->fetchCreatureImage($creatureId);
        $creatures['images'] = $images;
        return $this->respondWithData($creatures, 200);
    }

    function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile)
    {
        $filename = strtotime("now") . explode('.', $uploadedFile->getClientFilename())[0] . '.png';
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return explode(".", $filename)[0];
    }
}
