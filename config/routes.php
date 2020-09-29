<?php

use Slim\App;

use Slim\Interfaces\RouteCollectorProxyInterface as Group;

use App\Application\Actions\HomeAction;

use App\Application\Actions\User\UserCreateAction;
use App\Application\Actions\User\UserListAction;
use App\Application\Actions\User\UserUpdateAction;
use App\Application\Actions\User\UserDeleteAction;
use App\Application\Actions\User\FindUserByIdAction;

use App\Application\Actions\Species\FetchSpeciesAction;

use App\Application\Actions\Bo\BoListAction;
use App\Application\Actions\Ho\HoListAction;

use App\Application\Actions\Creatures\CreaturesListByFilterAction;
use App\Application\Actions\Creatures\CreaturesFindByIdAction;
use App\Application\Actions\Creatures\CreaturesRedBook;

use App\Application\Actions\Groups\FetchGroups;

use App\Application\Actions\Conbine\FetchFilterDataActioncs;

use App\Application\Actions\Posts\PostsFetchPostById;
use App\Application\Actions\Posts\FetchPosts;
use App\Application\Actions\Posts\FetchPostIdentify;

use App\Application\Actions\Category\FetchCategory;

return function(App $app) {
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });

    $app->get('/', HomeAction::class)->setName('home');

    $app->group('/users', function(Group $group) {
        $group->post('', UserCreateAction::class);
        $group->get('', UserListAction::class);
        $group->put('/{id}', UserUpdateAction::class);
        $group->delete('/{id}', UserDeleteAction::class);
        $group->get('/{id}', FindUserByIdAction::class);
    });
    
    $app->group('/species', function(Group $group) {
        $group->get('', FetchSpeciesAction::class);
    });

    $app->group('/groups', function(Group $group) {
        // $group->get('', GroupsListAction::class);
        $group->get('', FetchGroups::class);
    });
    
    $app->group('/bo', function(Group $group) {
        $group->get('', BoListAction::class);
    });

    $app->group('/ho', function(Group $group) {
        $group->get('', HoListAction::class);
    });

    $app->group('/creatures', function(Group $group) {
        $group->get('/red-book', CreaturesRedBook::class);
        $group->get('/{id}', CreaturesFindByIdAction::class);
        $group->get('', CreaturesListByFilterAction::class);
        
    });
    
    $app->group('/posts', function(Group $group) {
        $group->get('', FetchPosts::class);
        $group->get('/idetify', FetchPostIdentify::class);
        $group->get('/{id}', PostsFetchPostById::class);
    });

    $app->get('/fileimg/{imageName}', function ($request, $response, $args){
        $imageName = $args["imageName"];
        $file = __DIR__  . "/../assets/images/" . $imageName . ".jpg";
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

    $app->get('/filterData', FetchFilterDataActioncs::class);

    $app->group('/category', function(Group $group) {
        $group->get('', FetchCategory::class);
    });
    /**
     * Catch-all route to serve a 404 Not Found page if none of the routes match
     * NOTE: make sure this route is defined last
     */
    // $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    //     throw new HttpNotFoundException($request);
    // });
};