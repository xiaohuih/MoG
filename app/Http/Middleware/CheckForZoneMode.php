<?php

namespace App\Http\Middleware;

use App\Facades\Game;
use App\Exceptions\InvalidZoneException;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Middleware\Pjax;

use Closure;

class CheckForZoneMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (empty(Game::getZone())) {
            static::error();
        }
        return $next($request);
    }

    /**
     * Send error response page.
     */
    public static function error()
    {
        $response = response(Admin::content()->withError(trans('game.select_zone')));

        if (!request()->pjax() && request()->ajax()) {
            abort(403, trans('game.select_zone'));
        }

        Pjax::respond($response);
    }
}
