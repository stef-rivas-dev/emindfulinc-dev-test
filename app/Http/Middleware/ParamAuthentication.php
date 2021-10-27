<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ParamAuthentication
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
        if ($request->path() === 'api/users') {
            return $next($request);
        }

        $validated = $request->validate([
            'auth_user_id' => 'required|exists:users,id',
        ]);

        config(['app.auth_user_id' => (int)$validated['auth_user_id']]);

        return $next($request);
    }
}
