<?php

namespace App\Http\Middleware\Sentinel;

use Closure;
use Sentinel;

class Authenticate
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
        if (Sentinel::check() !== false) {
            return $next($request);
        }

        return redirect('login')->withInput()->with(['myerror' => trans('sentinel.permission_denied')]);
    }
}
