<?php

namespace App\Http\Middleware\Sentinel;

use Closure;
use Sentinel;
use Redirect;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $permission チェックするパーミッション
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (($user=Sentinel::check()) && $user->hasAccess($permission)) {
            return $next($request);
        }
        return Redirect::back()->withInput()->with(['myerror' => trans('sentinel.permission_denied')]);
    }
}
