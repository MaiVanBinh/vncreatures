<?php
namespace App\Application\Actions\Posts;

use App\Application\Actions\Posts\PostsActions;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class UpdatePost extends PostsActions {
    public function action() {
        try {
            $token = $this->request->getAttribute('token');
            $isUserExist = false;
            if($token) {
                $isUserExist = $this->checkUserExist($token['id']);
            }
            if($isUserExist) {
                $this->validator->validate($this->request, [
                    "id"=>v::digit(),
                    "title"=>v::notEmpty(),
                    "description"=>v::notEmpty(),
                    "content"=>v::notEmpty(),
                    'category'=>v::digit()
                ]);
                if($this->validator->failed())
                {
                    $responseMessage = $this->validator->errors;
                    return $this->respondWithData($responseMessage, 404);
                }
                $id = CustomRequestHandler::getParam($this->request, "id");
                $title = CustomRequestHandler::getParam($this->request, "title");
                $author = CustomRequestHandler::getParam($this->request, "author");
                $category = CustomRequestHandler::getParam($this->request, "category");
                $description = CustomRequestHandler::getParam($this->request, "description");
                $content = CustomRequestHandler::getParam($this->request, "content");
                $this->postsServices->updatePost( $id, $title, $author, $category, $description, $content, $token['id']);
                $postUpdate = $this->postsServices->fetchPostById($id);
                return $this->respondWithData($postUpdate);
            } else {
                return $this->respondWithData('Unauthorzied', 401);
            }
            
        } catch(Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}