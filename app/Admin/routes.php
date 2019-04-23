<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('zone', 'ZoneController@index');
    $router->post('zone/select', 'ZoneController@select');

    $router->get('/', 'HomeController@index');
    $router->resource('activity', ActivityController::class);
    $router->get('config/schedules/import', 'Config\ScheduleImportController@index');
    $router->post('config/schedules/import', 'Config\ScheduleImportController@store');
    $router->resource('config/schedules', Config\ScheduleController::class);
    $router->resource('gcode', GCodeController::class);

    $router->resource('player/search', Player\SearchController::class);
    $router->resource('player', PlayerController::class);
});
