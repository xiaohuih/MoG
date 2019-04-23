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
    $router->get('schedule/import', 'ScheduleImportController@index');
    $router->post('schedule/import', 'ScheduleImportController@store');
    $router->resource('schedule', ScheduleController::class);
    $router->resource('gcode', GCodeController::class);

    $router->resource('player/search', Player\SearchController::class);
    $router->resource('player', PlayerController::class);
});
