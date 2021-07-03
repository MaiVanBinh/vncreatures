<?php
namespace App\Application\Actions\Assets;
use App\Application\Actions\Assets\AssetsAction;

class  FetchAssetAction extends AssetsAction {
    public function action() {
        $fileName = $this->resolveArg('fileName');
        $images = $this->assetsServices->fetchAssetByFileName($fileName);
        if(count($images) === 1) { // __DIR__  .
            $file = __DIR__  . '/../../../../assets/images/' . $images[0]['name'];
            if (!file_exists($file)) {
                die("file:$file");
            }
            $image = file_get_contents($file);
            if ($image === false) {
                die("error getting image");
            }
            $this->response->getBody()->write($image);
            return $this->response->withHeader('Content-Type', 'image/png');
        }
        $this->response->getBody()->write("badrequest");
        return $this->response->withHeader('Content-Type', 'application/json');
    }
}