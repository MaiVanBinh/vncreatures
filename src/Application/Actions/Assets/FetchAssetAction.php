<?php
namespace App\Application\Actions\Assets;
use App\Application\Actions\Assets\AssetsAction;

class  FetchAssetAction extends AssetsAction {
    public function action() {
        $fileName = $this->resolveArg('fileName');
        $file = __DIR__  . '/../../../../assets/images/' . $fileName . '.png';
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
}