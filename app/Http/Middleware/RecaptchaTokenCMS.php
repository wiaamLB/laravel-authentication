<?php

namespace App\Http\Middleware;

use Closure;

class RecaptchaTokenCMS
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
        if (request()->has('g-recaptcha-response')) {
            $response = Http::asForm()->post('https://google.com/recaptcha/api/siteverify', ['secret' => config('recaptcha.secret_cms'), 'response' => request('g-recaptcha-response')]);
            if ($response->json()['success']) {
                return $next($request);
            } else
                return response(['status'=>false, 'data' => 'Invalid recaptcha'],403);

        }
        return response(['status'=>false, 'data' => 'No recaptcha found'],403);
    }
}
