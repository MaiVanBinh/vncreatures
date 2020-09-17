<?php

namespace App\Application\Actions\Posts;
use App\Application\Actions\Posts\PostsActions;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class FetchPostIdentify extends PostsActions {
    public function action() {
        try{
            $identify = $this->postsServices->fetchPostIndentify();
            return $this->respondWithData($identify);
        } catch(Exception $err) {
            throw new HttpInternalServerErrorException($this->request, $err->getMessage());
        }
    }
}



