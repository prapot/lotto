<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class checkToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {   
        $bearer = $request->bearerToken();
        if($bearer != config('app.api_token')){
            return response()->json(['message' => 'Unauthenticated.']);
        }

        return $next($request);
    }
}
