<?php

namespace App\Application\Actions\Posts;
use App\Application\Actions\Posts\PostsActions;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class FetchPostIdentify extends PostsActions {
    public function action() {
        try{
            $identify = $this->postsServices->fetchPostIndentify();
            $animal = [];
            $plant = [];
            $insect = [];
            for ($i = 0; $i < count($identify); $i++) {
                switch ($identify[$i]['category']) {
                    case '3':
                        array_push($animal, $identify[$i]);
                        break;
                    case '4':
                        array_push($plant, $identify[$i]);
                        break;
                    case '5':
                        array_push($insect, $identify[$i]);
                        break;
                }
            }
            return $this->respondWithData(['animal' => $animal, 'plant' => $plant, 'insect' => $insect]);
            return $this->respondWithData($identify);
        } catch(Exception $err) {
            throw new HttpInternalServerErrorException($this->request, $err->getMessage());
        }
    }
}