<?php

use Slim\App;

use Slim\Interfaces\RouteCollectorProxyInterface as Group;

use App\Application\Actions\HomeAction;

use App\Application\Actions\Orders\FetchOrders;

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

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function(App $app) {
    
    $app->options('/vnback/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);
        return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    });

    $app->get('/vnback/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello World');
        return $response;
    });
    
    $app->group('/vnback/species', function(Group $group) {
        $group->get('', \App\Application\Actions\Species\FetchSpeciesAction::class);
        $group->post('', \App\Application\Actions\Species\CreateAction::class);
    });

    $app->group('/vnback/groups', function(Group $group) {
        // $group->get('', GroupsListAction::class);
        $group->get('', FetchGroups::class);
    });
    
    $app->group('/vnback/orders', function(Group $group) {
        $group->get('', FetchOrders::class);
    });

    $app->group('/vnback/families', function(Group $group) {
        $group->get('', \App\Application\Actions\Families\FetchFamilies::class);
    });

    $app->group('/vnback/creatures', function(Group $group) {
        $group->get('/red-book', CreaturesRedBook::class);
        $group->get('/{id}', CreaturesFindByIdAction::class);
        $group->get('', CreaturesListByFilterAction::class);
    });
    $app->group('/vnback/feedbacks', function(Group $group) {
        // $group->get('', GroupsListAction::class);
        $group->post('', \App\Application\Actions\Feedbacks\CreateFeedback::class);
        $group->get('', \App\Application\Actions\Feedbacks\FetchFeedbacks::class);
    });

    $app->group('/vnback/posts', function(Group $group) {
        $group->get('', FetchPosts::class);
        $group->get('/idetify', FetchPostIdentify::class);
        $group->get('/{id}', PostsFetchPostById::class);
    });
    $app->group('/vnback/footprint', function(Group $group) {
        $group->get('', \App\Application\Actions\Footprint\FetchFootprint::class);
    });
    
    $app->group('/vnback/auth/footprint', function(Group $group) {
        $group->post('', \App\Application\Actions\Footprint\Create::class);
        $group->delete('/{id}', \App\Application\Actions\Footprint\Delete::class);
        $group->put('/{id}', \App\Application\Actions\Footprint\Update::class);
    });
    
    $app->get('/vnback/fileimg/{imageName}', function ($request, $response, $args){
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

    $app->get('/vnback/filterData', FetchFilterDataActioncs::class);
    $app->get('/vnback/count', \App\Application\Actions\Conbine\Count::class);

    $app->group('/vnback/category', function(Group $group) {
        $group->get('', FetchCategory::class);
    });

    $app->group('/vnback/woods', function(Group $group) {
        $group->get('', \App\Application\Actions\Wood\FetchWood::class);
    });
    
    $app->group('/vnback/auth/woods', function(Group $group) {
        $group->post('', \App\Application\Actions\Wood\Create::class);
        $group->delete('/{id}', \App\Application\Actions\Wood\Delete::class);
        $group->put('/{id}', \App\Application\Actions\Wood\Update::class);
    });

    $app->group('/vnback/national-parks', function(Group $group) {
        $group->get('', FetchNationParks::class);
        $group->get('/{id}', FetchNationalParkById::class);
    });

    $app->group('/vnback/author', function(Group $group) {
        $group->get('', FetchAuthors::class);
    });

    $app->group('/vnback/latin-dic', function(Group $group) {
        $group->get('', SearchLatinDic::class);
    });

    $app->group('/vnback/users', function(Group $group) {
        $group->post('/login', \App\Application\Actions\User\Login::class);
        $group->post('/sign-up', \App\Application\Actions\User\Register::class);
    });

    $app->group('/vnback/auth/users', function(Group $group) {
        $group->get('/{id}', \App\Application\Actions\User\FetchUserById::class);
        $group->get('', \App\Application\Actions\User\FetchUsersAction::class);
        $group->delete('/{id}', \App\Application\Actions\User\UserDeleteAction::class);
        $group->post('/change-password', \App\Application\Actions\User\ChangePassword::class);
        $group->post('/change-password-sadmin', \App\Application\Actions\User\ChangePasswordSAdmin::class);
    });

    $app->group('/vnback/auth/creatures', function(Group $group) {
        $group->post('', \App\Application\Actions\Creatures\CreateCreature::class);
        $group->put('/{id}', \App\Application\Actions\Creatures\CreatureEditAction::class);
        $group->delete('/{id}', \App\Application\Actions\Creatures\DeleteCreature::class);
    });
    $app->group('/vnback/auth/species', function(Group $group) {
        $group->post('', \App\Application\Actions\Species\CreateAction::class);
        $group->delete('/{id}', \App\Application\Actions\Species\DeleteAction::class);
        $group->put('/{id}', \App\Application\Actions\Species\UpdateSpecies::class);
    });

    $app->group('/vnback/auth/groups', function(Group $group) {
        $group->post('', \App\Application\Actions\Groups\CreateGroup::class);
        $group->delete('/{id}', \App\Application\Actions\Groups\DeleteGroup::class);
        $group->put('/{id}', \App\Application\Actions\Groups\UpdateGroup::class);
    });

    $app->group('/vnback/auth/orders', function(Group $group) {
        $group->post('', \App\Application\Actions\Orders\CreateOrder::class);
        $group->delete('/{id}', \App\Application\Actions\Orders\DeleteOrder::class);
        $group->put('/{id}', \App\Application\Actions\Orders\UpdateOrder::class);
    });

    $app->group('/vnback/auth/families', function(Group $group) {
        $group->post('', \App\Application\Actions\Families\CreateFamily::class);
        $group->delete('/{id}', \App\Application\Actions\Families\DeleteFamily::class);
        $group->put('/{id}', \App\Application\Actions\Families\UpdateFamily::class);
    });

    $app->group('/vnback/auth/posts', function(Group $group) {
        $group->get('/{id}', PostsFetchPostById::class);
        $group->post('', \App\Application\Actions\Posts\CreatePost::class);
        $group->delete('/{id}', \App\Application\Actions\Posts\DeletePost::class);
        $group->put('/{id}', \App\Application\Actions\Posts\UpdatePost::class);
        $group->get('', \App\Application\Actions\Posts\FetchPostsAuth::class);
    });

    $app->get('/vnback/assets/{fileName}', \App\Application\Actions\Assets\FetchAssetAction::class);

    $app->group('/vnback/auth/assets', function(Group $group) {
        $group->get('', \App\Application\Actions\Assets\FetchAsset::class);
        $group->post('', \App\Application\Actions\Assets\CreateAsset::class);
        $group->delete('/{id}', \App\Application\Actions\Assets\DeleteAsset::class);
    });
    
    $app->group('/vnback/auth/category', function(Group $group) {
        $group->post('', \App\Application\Actions\Category\CreateCategory::class);
        $group->delete('/{id}', \App\Application\Actions\Category\DeteleCatogory::class);
        $group->put('/{id}', \App\Application\Actions\Category\UpdateCategory::class);
    });
    
    
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/vnback/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });
};