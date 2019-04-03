<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->resource('player', PlayerController::class);
    $router->resource('activity', ActivityController::class);
    $router->resource('schedule', ScheduleController::class);
    $router->post('schedule/import', 'ScheduleController@import');
    $router->resource('gcode', GCodeController::class);
    $router->resource('maintenance/notice', Maintenance\NoticeController::class);
    $router->resource('maintenance/servers', Maintenance\ServersController::class);
});
