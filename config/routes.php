<?php

use Slim\App;

use App\Application\Actions\HomeAction;
use App\Application\Actions\User\UserCreateAction;
use App\Application\Actions\User\UserListAction;
use App\Application\Actions\User\UserUpdateAction;
use App\Application\Actions\User\UserDeleteAction;
use App\Application\Actions\User\FindUserByIdAction;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function(App $app) {

    $app->get('/', HomeAction::class)->setName('home');

    $app->group('/users', function(Group $group) {
        $group->post('', UserCreateAction::class);
        $group->get('', UserListAction::class);
        $group->put('/{id}', UserUpdateAction::class);
        $group->delete('/{id}', UserDeleteAction::class);
        $group->get('/{id}', FindUserByIdAction::class);
    });
    $app->get('/fileimg', function ($request, $response){
        $file = __DIR__  . "/uploads/back.jpg";
        if (!file_exists($file)) {
            die("file:$file");
        }
        $image = file_get_contents($file);
        if ($image === false) {
            die("error getting image");
        }
        $response->getBody()->write($image);
        return $response->withHeader('Content-Type', 'image/png');
    });
};