<?php

use Slim\App;

use Slim\Interfaces\RouteCollectorProxyInterface as Group;

use App\Application\Actions\HomeAction;

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

use App\Application\Actions\NationalParks\FetchNationalParkById;
use App\Application\Actions\NationalParks\FetchNationParks;

use App\Application\Actions\Author\FetchAuthors;

use App\Application\Actions\LatinDic\SearchLatinDic;
use Slim\Exception\HttpNotFoundException;

return function(App $app) {
    
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });

    $app->get('/', HomeAction::class)->setName('home');
    
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

    $app->group('/national-parks', function(Group $group) {
        $group->get('', FetchNationParks::class);
        $group->get('/{id}', FetchNationalParkById::class);
    });

    $app->group('/author', function(Group $group) {
        $group->get('', FetchAuthors::class);
    });

    $app->group('/latin-dic', function(Group $group) {
        $group->get('', SearchLatinDic::class);
    });

    $app->group('/users', function(Group $group) {
        $group->post('/login', \App\Application\Actions\User\Login::class);
        $group->post('/sign-up', \App\Application\Actions\User\Register::class);
    });

    $app->group('/auth/users', function(Group $group) {
        $group->get('/{id}', \App\Application\Actions\User\FetchUserById::class);
    });

    $app->group('/auth/creatures', function(Group $group) {
        $group->post('/{id}', \App\Application\Actions\Creatures\CreatureEditAction::class);
    });

    $app->get('/assets/{fileName}', \App\Application\Actions\Assets\FetchAssetAction::class);

    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });
};