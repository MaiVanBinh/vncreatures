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
                $result = $this->services->fetchLatinDic($query['latin']);
            }
            if(!$result || count($result) === 0) {
                throw new Exception('Not Found');
            } else {
                return $this->respondWithData($result[0]);
            }
        } catch(Exception $err) {
            throw new HttpNotFoundException($this->request, $err->getMessage());
        }
    } 
}