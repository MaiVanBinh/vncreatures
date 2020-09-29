<?php

namespace App\Application\Actions\Category;
use App\Application\Actions\Category\CategoryAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class FetchCategory extends CategoryAction {
    public function action() {
        try {
            $category = $this->categoryServices->fetchCategory();
            return $this->respondWithData($category);
        } catch(Exception $err) {
            throw new HttpInternalServerErrorException($this->request, $err->getMessage());
        }
    }
}