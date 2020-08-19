<?php

use Slim\App;
use App\Application\Middleware\BeforeMiddleware;
use App\Application\Middleware\AfterMiddleware;
use App\Application\Middleware\SessionMiddleware;

return function(App $app) {

    // Add middleware
    // $app->add(BeforeMiddleware::class);
    // $app->add(AfterMiddleware::class);
    $app->addBodyParsingMiddleware();
    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);
        return $response
                ->withHeader('Access-Control-Allow-Origin', 'http://localhost:3000')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });
    // $app->add(SessionMiddleware::class);
};