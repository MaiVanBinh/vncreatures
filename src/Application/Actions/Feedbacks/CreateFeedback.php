<?php

namespace App\Application\Actions\Feedbacks;

use Exception;
use Slim\Exception\HttpInternalServerErrorException;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use App\Application\Actions\Feedbacks\FeedbacksAction;

class CreateFeedback extends FeedbacksAction
{
    public function action()
    {
        try {
            $this->validator->validate($this->request, [
                "message" => v::notEmpty(),
                "star" => v::intType()->max(5)->min(1)
            ]);
            if ($this->validator->failed()) {
                $responseMessage = $this->validator->errors;
                return $this->respondWithData($responseMessage, 400);
            }
            $message = CustomRequestHandler::getParam($this->request, "message");
            $star = CustomRequestHandler::getParam($this->request, "star");
            $email = CustomRequestHandler::getParam($this->request, "email");
            if(!$email) $email = '';
            $this->fbServices->create($email, $message, $star);
            return $this->respondWithData("create feedbacks success", 201);
        } catch (Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}
