<?php

use Slim\App;

use Slim\Interfaces\RouteCollectorProxyInterface as Group;

use App\Application\Actions\HomeAction;
use App\Application\Actions\User\UserCreateAction;
use App\Application\Actions\User\UserListAction;
use App\Application\Actions\User\UserUpdateAction;
use App\Application\Actions\User\UserDeleteAction;
use App\Application\Actions\User\FindUserByIdAction;
use App\Application\Actions\Species\SpeciesListAction;
use App\Application\Actions\Classes\ClassesListAction;
use App\Application\Actions\Bo\BoListAction;
use App\Application\Actions\Ho\HoListAction;
use App\Application\Actions\Creatures\CreaturesListAction;
return function(App $app) {

    $app->get('/', HomeAction::class)->setName('home');

    $app->group('/users', function(Group $group) {
        $group->post('', UserCreateAction::class);
        $group->get('', UserListAction::class);
        $group->put('/{id}', UserUpdateAction::class);
        $group->delete('/{id}', UserDeleteAction::class);
        $group->get('/{id}', FindUserByIdAction::class);
    });
    
    $app->group('/species', function(Group $group) {
        $group->get('', SpeciesListAction::class);
    });

    $app->group('/classes', function(Group $group) {
        $group->get('', ClassesListAction::class);
    });
    
    $app->group('/bo', function(Group $group) {
        $group->get('', BoListAction::class);
    });

    $app->group('/ho', function(Group $group) {
        $group->get('', HoListAction::class);
    });

    $app->group('/creatures', function(Group $group) {
        $group->get('', CreaturesListAction::class);
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