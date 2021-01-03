<?php

namespace App\Application\Actions\Posts;
use App\Application\Actions\Posts\PostsActions;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class FetchPosts extends PostsActions {
    public function action() {
        try {
            $query = $this->request->getQueryParams();
            $limit = array_key_exists('limit', $query) && $query['limit'] ? $query['limit'] : 5;
            $page = array_key_exists('page', $query) && $query['page'] ? $query['page'] : 1;
            $category = array_key_exists('category', $query) && $query['category'] ? $query['category'] : '';
            $title = array_key_exists('title', $query) && $query['title'] ? $query['title'] : null;
            $posts = $this->postsServices->fetchPosts($category, $limit, $page, $title);
            return $this->respondWithData($posts);
        } catch(Exception $err) {
            throw new HttpInternalServerErrorException($this->request, $err->getMessage());
        }
    }
} 