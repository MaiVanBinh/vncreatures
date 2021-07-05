<?php

namespace App\Application\Actions\Posts;

use App\Application\Actions\Posts\PostsActions;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class UpdatePost extends PostsActions
{
    public function action()
    {
        try {
            $token = $this->request->getAttribute('token');
            $id = (int) $this->resolveArg('id');
            if($id) {
                // check valid of body param of request
                $this->validator->validate($this->request, [
                    "title" => v::notEmpty(),
                    "description" => v::notEmpty(),
                    "language" => v::notEmpty(),
                    'category' => v::digit(),
                    'is_publish' => v::boolType()
                    
                ]);
                if ($this->validator->failed()) {
                    $responseMessage = $this->validator->errors;
                    return $this->respondWithData($responseMessage, 400);
                }
    
                $title = CustomRequestHandler::getParam($this->request, "title");
                $category = (int) CustomRequestHandler::getParam($this->request, "category");
                $description = CustomRequestHandler::getParam($this->request, "description");
                $content = CustomRequestHandler::getParam($this->request, "content");
                $is_publish = CustomRequestHandler::getParam($this->request, "is_publish");
                $language =  CustomRequestHandler::getParam($this->request, "language") == 'en' ? 'en' : 'vn';

                if(!$this->categoryExist($category)) {
                    return $this->respondWithData("Categories is not exist", 400); 
                }
                // get list images add image
                $imagesAdd = CustomRequestHandler::getParam($this->request, "imageAdd");
                if(is_array($imagesAdd)) {
                    for($i = 0; $i < count($imagesAdd); $i++) {
                        $isImageExist = (int)$this->checkImageExistById($imagesAdd[$i]);
                        if(!$isImageExist) {
                            return $this->respondWithData("Link Image: {$imagesAdd[$i]} is not exits", 400);
                        }
                    }
                }
                // update link image with post
                for($i = 0; $i < count($imagesAdd); $i++) {
                    $this->PIServices->linkPostWithImage($id, $imagesAdd[$i], $token['id']);
                    $this->assetsServices->useImage($imagesAdd[$i], true);
                }

                $imagesRemove = CustomRequestHandler::getParam($this->request, "imagesRemove");

                if(is_array($imagesRemove)) {
                    for($i = 0; $i < count($imagesRemove); $i++) {
                        $isImageExist = (int)$this->checkImageExistById($imagesRemove[$i]);
                        if(!$isImageExist) {
                            return $this->respondWithData("Link Image: {$imagesRemove[$i]} is not exits", 400);
                        }
                        $this->unLinkImageWithPost($imagesRemove[$i], $id);
                    }
                }

                // update new Post
                $this->postsServices->updatePost($id, $title, $category, $description, $content, $is_publish, $token['id'], $language);
  
                $newPost = $this->postsServices->fetchPostById($id);
                return $this->respondWithData($newPost);
            } 
            
        } catch (Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}
