<?php

use Slim\App;
use App\Application\Middleware\CorsMiddleware;

return function (App $app) {
    $app->addBodyParsingMiddleware();
    $app->add(CorsMiddleware::class);
    $app->addRoutingMiddleware();
    $app->add(new Tuupola\Middleware\JwtAuthentication([
        "path" => "/vnback/auth/",
        "secure" => false,
        "secret" => "DoAnhBatDuocEm",
        "algorithm" => ["HS256"]
    ]));
};