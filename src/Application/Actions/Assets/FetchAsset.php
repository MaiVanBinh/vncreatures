<?php
namespace App\Application\Actions\Assets;
use App\Application\Actions\Assets\AssetsAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class  FetchAsset extends AssetsAction {
    public function action() {
        try {
            $filter = $this->request->getQueryParams();
            $page = array_key_exists('page', $filter) ? intval($filter['page']) : null; 
            $assets = $this->assetsServices->fetchAsset($page);
            $total = $this->assetsServices->countEntries();
            return $this->respondWithData(["total" => $total, "assets" => $assets]);
        } catch(Exception $ex) {
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }
}