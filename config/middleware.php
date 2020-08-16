<?php

use Slim\App;
use App\Application\Middleware\BeforeMiddleware;
use App\Application\Middleware\AfterMiddleware;
use App\Application\Middleware\SessionMiddleware;

return function(App $app) {

    // Add middleware
    // $app->add(BeforeMiddleware::class);
    // $app->add(AfterMiddleware::class);
    $app->add(SessionMiddleware::class);

};