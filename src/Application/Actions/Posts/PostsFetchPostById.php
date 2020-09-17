<?php

namespace App\Application\Actions\Posts;
use App\Application\Actions\Posts\PostsActions;
use Exception;
use Slim\Exception\HttpNotFoundException;

class PostsFetchPostById extends PostsActions {
    public function action() {
        try{
            $id = $this->resolveArg('id');
            $post = $this->postsServices->fetchPostById($id);
            if(!$post) {
                throw new Exception('Not Found');
            }
            return $this->respondWithData($post);
        } catch(Exception $err) {
            throw new HttpNotFoundException($this->request, $err->getMessage());
        }
        
    }
}