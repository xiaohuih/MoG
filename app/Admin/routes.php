<?php

use App\Facades\Game;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Input;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->post('selectzone', function () {
        Game::setZone(Input::get('_zone', '0'));
    });
    $router->get('/', 'HomeController@index');
    $router->resource('activity', ActivityController::class);
    $router->get('schedule/import', 'ScheduleImportController@index');
    $router->post('schedule/import', 'ScheduleImportController@store');
    $router->resource('schedule', ScheduleController::class);
    $router->resource('gcode', GCodeController::class);

    $router->resource('player/search', Player\SearchController::class);
    $router->resource('player', PlayerController::class);
});
