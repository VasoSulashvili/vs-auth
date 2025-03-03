<?php

namespace VS\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use VS\Auth\Enums\TwoFAScope;
use VS\Auth\Enums\TwoFAType;
use VS\Base\Classes\API;

class TwoFAVerifyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->two_fa_type !== TwoFAType::NONE->name && !$request->user()->tokenCan(TwoFAScope::TwoFAVerified->value)) {
            return API::error('Two Factor Authentication is required.', 403);
        }
        return $next($request);
    }
}
