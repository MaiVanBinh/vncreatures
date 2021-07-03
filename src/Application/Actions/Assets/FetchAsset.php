<?php

namespace App\Application\Actions\Assets;

use App\Application\Actions\Assets\AssetsAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class  FetchAsset extends AssetsAction
{
    public function action()
    {
        try {
            $filter = $this->request->getQueryParams();
            $page = array_key_exists('page', $filter) ? intval($filter['page']) : 1;
            $limit = array_key_exists('limit', $filter) ? intval($filter['limit']) : 10;
            $name = array_key_exists('name', $filter) ? $filter['name'] : '';
            $assets = $this->assetsServices->fetchAsset($page, $limit, $name);

            // get page
            $total = (int)$assets['total'];
            $maxPage = ceil($total / $limit);
            $hasPrev = $page == 1 || $page - 1 > $maxPage ? false : true;
            $hasNext = $page >= $maxPage ? false : true;
            $assets['pages'] = ['total' => $maxPage, 'current' => $page, 'prev' => $page - 1, 'next' => $page + 1, 'hasPrev' => $hasPrev, 'hasNext' => $hasNext];
            return $this->respondWithData($assets, 200);
        } catch (Exception $ex) {
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }
}