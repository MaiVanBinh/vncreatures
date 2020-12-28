<?php

namespace App\Application\Actions\Creatures;

use App\Application\Actions\Creatures\CreaturesActions;
use Exception;
use Psr\Http\Message\UploadedFileInterface;

class CreateCreature extends CreaturesActions
{
    public function action()
    {
        try {
            $token = $this->request->getAttribute('token');
            $creature = $this->request->getParsedBody();
            $id = $this->creaturesServices->createCreature($creature, $token['id']);

            //upload file
            $directory = __DIR__ . '/../../../../assets/images';

            // Get all file upload
            $uploadedFiles = $this->request->getUploadedFiles();

            $numberImage = (int)$creature['numberImage'];

            for ($i = 0; $i < $numberImage; $i++) {
                $uploadedFile = $uploadedFiles["image{$i}"];
                $filename = null;
                if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
                    $filename = $this->moveUploadedFile($directory, $uploadedFile);
                    $uri = $this->request->getUri();
                    $imageUrl = $uri->getScheme() . '://' . $uri->getHost();
                    if ($uri->getHost() === 'localhost') {
                        $imageUrl .= ':' . $uri->getPort();
                    }
                    $imageUrl .= "/assets/" . $filename;
                    $idAsset = $this->assetsServices->createAsset($imageUrl, $filename);
                    $this->acServices->createNewOne($idAsset, (int)$id);
                }
                if (!$filename) {
                    throw new Exception('Upload image error');
                }
            }

           
            // $creatures = $this->creaturesServices->fetchCreatureById($creatureId);
            // $images = $this->assetsServices->fetchCreatureImage($creatureId);
            // $creatures['images'] = $images;
            return $this->respondWithData($id, 200);
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
