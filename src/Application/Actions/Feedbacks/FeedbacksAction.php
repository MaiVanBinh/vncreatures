<?php

namespace App\Application\Actions\Feedbacks;

use Psr\Log\LoggerInterface;
use App\Application\Actions\Actions;
use App\Application\Actions\Validator;
use Exception;
use App\Domain\Feedbacks\FeedbacksServices;

abstract class FeedbacksAction extends Actions{
    protected $fbServices;
    protected $validator;

    public function __construct(LoggerInterface $logger, FeedbacksServices $fbServices, Validator $validator)
    {
        parent::__construct($logger);
        $this->fbServices = $fbServices;
        $this->validator = $validator;
    }

}