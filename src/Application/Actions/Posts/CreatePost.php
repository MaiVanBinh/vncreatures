<?php

namespace App\Application\Actions\Posts;

use App\Application\Actions\Posts\PostsActions;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class CreatePost extends PostsActions
{
    public function action()
    {
        try {
            $token = $this->request->getAttribute('token');
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
            $category = CustomRequestHandler::getParam($this->request, "category");
            $description = CustomRequestHandler::getParam($this->request, "description");
            $content = CustomRequestHandler::getParam($this->request, "content");
            $is_publish = CustomRequestHandler::getParam($this->request, "is_publish");
            $language =  CustomRequestHandler::getParam($this->request, "language") == 'en' ? 'en' : 'vn';
            // get list images
            $images = CustomRequestHandler::getParam($this->request, "images");

            for($i = 0; $i < count($images); $i++) {
                $isImageExist = (int)$this->checkImageExistById($images[$i]);
                if(!$isImageExist) {
                    return $this->respondWithData("Image: {$images[$i]} is not exits", 400);
                }
            }
            // create new Post
            $newId = $this->postsServices->createPost($title, $category, $description, $content, $token['id'], $is_publish,  $language);
            
            // link image with post
            for($i = 0; $i < count($images); $i++) {
                $this->PIServices->linkPostWithImage($newId, $images[$i], $token['id']);
                $this->assetsServices->useImage($images[$i], true);
            }
            $newPost = $this->postsServices->fetchPostById($newId);
            return $this->respondWithData($newPost);
        } catch (Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}
