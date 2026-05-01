<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTeamContext
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! empty(auth()->user())) {
            if (auth()->user()->team_id) {
                setPermissionsTeamId(auth()->user()->team_id);
            }
        }

        return $next($request);
    }
}
