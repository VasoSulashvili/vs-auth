<?php

namespace VS\Auth\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;

class VSAuthClientMiddleware extends CheckClientCredentials
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$scopes)
    {
        try {
            return parent::handle($request, $next, ...$scopes);
        } catch (AuthenticationException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Unauthorized',
                'message' => 'Invalid client credentials.',
            ], 401);
        }
    }
}
