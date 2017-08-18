<?php

namespace App\Http\Middleware;

use Closure;

class Approved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $active = \App\Driver::isApproved($request->user()->id);
        if(!$active)
            $request['isHired'] = 3;
        else
            $request['isHired'] = $request->active;
        $response = $next($request);

        // Post-Middleware Action

        return $response;
    }
}
