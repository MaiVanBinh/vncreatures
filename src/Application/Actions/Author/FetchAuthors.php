<?php

namespace App\Application\Actions\Author;
use App\Application\Actions\Author\AuthorAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class FetchAuthors extends AuthorAction {
    public function action() {
        try {
            $query = $this->request->getQueryParams();
            $page = 1;
            if(array_key_exists('page', $query) && $query['page']) {
                $page = intval($query['page']);
            }
            $all = false;
            if(array_key_exists('all', $query)) {
                $all = true;
            }
            $author = $this->aServices->fetchAuthors($page, $all);
            return $this->respondWithData($author);
        } catch (Exception $err) {
            throw new HttpInternalServerErrorException($this->request, $err->getMessage());
        }
    }
}