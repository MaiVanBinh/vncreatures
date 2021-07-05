<?php

namespace App\Application\Actions\Posts;
use App\Application\Actions\Posts\PostsActions;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;


class DeletePost extends PostsActions {
    public function action() {
        try {
            $token = $this->request->getAttribute('token');
            $isUserExist = false;
            if($token) {
                $isUserExist = $this->checkUserExist($token['id']);
            }
            if($isUserExist) {
                $id = (int) $this->resolveArg('id');
                if(!$id)
                {
                    $responseMessage = $this->validator->errors;
                    return $this->respondWithData($responseMessage, 400);
                }
                $imagesOfPost = $this->assetsServices->fetchAssetByPostId($id);
                for($i=0; $i < count($imagesOfPost); $i++) {
                    $this->unLinkImageWithPost($imagesOfPost[$i]['id'], $id);
                    
                }
                $this->postsServices->deletePost($id);
                return $this->respondWithData("Delete success", 200);
            } else {
                return $this->respondWithData('Unauthorzied', 401);
            }
            
        } catch(Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}