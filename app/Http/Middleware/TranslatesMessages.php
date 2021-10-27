<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TranslatesMessages
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
        if (!$request->hasHeader('Accept-Language')) {
            return $next($request);
        }

        $locale = $request->header('Accept-Language');

        config(['app.locale' => $locale]);

        return $next($request);
    }
}
