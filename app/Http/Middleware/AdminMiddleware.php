<?php

namespace App\Http\Middleware;

use App\Enums\AccessType;
use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (!Auth::guard($guard)->check()) {
            return response('Unauthorized', 401);
        }

        /** @var User $user */
        $user = auth()->user();

        if ($user->access_type == AccessType::ADMIN) {
            return $next($request);
        }

        abort(401);
    }
}
