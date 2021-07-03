<?php

namespace App\Application\Actions\Posts;

use App\Application\Actions\Posts\PostsActions;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class FetchPostsAuth extends PostsActions
{
    public function action()
    {
        try {
            $token = $this->request->getAttribute('token');
            $query = $this->request->getQueryParams();
            $limit = array_key_exists('limit', $query) && $query['limit'] ? $query['limit'] : 5;
            $page = array_key_exists('page', $query) && $query['page'] ? $query['page'] : 1;
            $category = array_key_exists('category', $query) && $query['category'] ? json_decode($query['category']) : '';
            $title = array_key_exists('title', $query) && $query['title'] ? $query['title'] : null;
            $dateFrom = array_key_exists('dateFrom', $query) && $query['dateFrom'] ? $query['dateFrom'] : null;
            $dateTo = array_key_exists('dateTo', $query) && $query['dateTo'] ? $query['dateTo'] : null;
            $is_publish = array_key_exists('is_publish', $query) ? $query['is_publish'] : 'all';
            $language = array_key_exists('language', $query) && $query['language'] ? $query['language'] : 'all';
            $posts = $this->postsServices->fetchPostsAuth($category, $limit, $page, $title, $dateFrom, $dateTo, $is_publish, $language);
            // get page
            $total = $posts['total'];
            $maxPage = ceil($total / $limit);
            $hasPrev = $page == 1 || $page -1 > $maxPage ? false : true;
            $hasNext = $page >= $maxPage ? false : true;
            $posts['pages'] = ['total' => $maxPage, 'current' => $page, 'prev' => $page - 1, 'next' => $page + 1, 'hasPrev' => $hasPrev, 'hasNext' => $hasNext];
            return $this->respondWithData($posts);
        } catch (Exception $err) {
            throw new HttpInternalServerErrorException($this->request, $err->getMessage());
        }
    }
}