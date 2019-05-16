<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('zones', 'ZoneController@zones');
    $router->get('zone', 'ZoneController@index');
    $router->post('zone/select', 'ZoneController@select');

    $router->get('/', 'HomeController@index');
    $router->resource('activity', ActivityController::class);
    $router->get('config/schedules/import', 'Config\ScheduleImportController@index');
    $router->post('config/schedules/import', 'Config\ScheduleImportController@store');
    $router->resource('config/schedules', Config\ScheduleController::class);
    $router->resource('config/roles', Config\GameRoleController::class);
    $router->resource('mail', MailController::class);
    $router->resource('notice', NoticeController::class);
    $router->get('gcode/export/{id}', 'GCodeController@export');
    $router->resource('gcode', GCodeController::class);
    $router->resource('script', ScriptController::class);
    // 区服操作
    $router->group(['middleware' => 'zone'], function (Router $router) {
        // 玩家
        $router->resource('player/search', Player\SearchController::class);
        $router->resource('player/ranks', Player\RankController::class);
        $router->resource('player/pets', Player\PetController::class);
        $router->resource('player/items', Player\ItemController::class);
        // 公会
        $router->resource('guild/search', Guild\SearchController::class);
        $router->resource('guild/ranks', Guild\RankController::class);
        $router->resource('guild/members', Guild\MemberController::class);
    });
});
