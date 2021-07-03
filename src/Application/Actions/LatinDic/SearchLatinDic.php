<?php
namespace App\Application\Actions\LatinDic;
use App\Application\Actions\LatinDic\LatinDicAction;
use Exception;
use Slim\Exception\HttpNotFoundException;

class SearchLatinDic extends LatinDicAction {
    public function action() {
        try {
            $query = $this->request->getQueryParams();
            $result = null;
            if(array_key_exists('latin', $query) && $query['latin']) {
                $result = $this->services->latinToViet($query['latin']);
            }
            if(array_key_exists('viet', $query) && $query['viet']) {
                $result = $this->services->VietToLatin($query['viet']);
            }
            if(!$result || count($result) === 0) {
                return $this->respondWithData('Not Found', 404);
            } else {
                return $this->respondWithData($result);
            }
        } catch(Exception $err) {
            throw new HttpNotFoundException($this->request, $err->getMessage());
        }
    } 
}