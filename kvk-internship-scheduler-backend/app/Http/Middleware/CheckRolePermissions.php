<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserProfile;

class CheckRolePermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$levels)
    {
        $userRoleLevel = UserProfile::query()->find($request->input('user_id'));

        if(!in_array($userRoleLevel, $levels)) {
            return response('Unauthorized', 401);
        }
        return $next($request);
    }
}
