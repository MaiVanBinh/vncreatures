<?php

namespace App\Application\Actions\Posts;

use App\Application\Actions\Actions;
use Psr\Log\LoggerInterface;
use App\Domain\Posts\PostsServices;

abstract class PostsActions extends Actions {
    protected $postsServices;

    public function __construct(PostsServices $service, LoggerInterface $logger)
    {
        parent::__construct($logger);
        $this->postsServices = $service;
    }
}