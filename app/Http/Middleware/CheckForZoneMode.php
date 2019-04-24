<?php

namespace App\Http\Middleware;

use App\Facades\Game;
use App\Exceptions\InvalidZoneException;

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
        if (!empty(Game::getZone())) {
            return $next($request);
        }
        return response()->view('invalidzone');
        //throw new InvalidZoneException;
    }
}
