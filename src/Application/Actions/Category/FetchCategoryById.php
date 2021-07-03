<?php

namespace App\Application\Actions\Category;

use App\Application\Actions\Category\CategoryAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpUnauthorizedException;

class FetchCategoryById extends CategoryAction
{
    /**
     * {@inheritdoc}
     */
    protected function action()
    {
        try {
            $id = $this->resolveArg('id');
            $categories = $this->categoryServices->fetchCategoryById($id);
            if(count($categories) == 0 ) {
                return $this->respondWithData("Category not found", 404);
            }
            return $this->respondWithData($categories[0], 200);
        } catch (Exception $ex) {
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }
}
