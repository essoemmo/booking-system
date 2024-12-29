<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('api')->check() && auth('api')->user()->is_admin) {
            return $next($request);
        }
        return self::failResponse(403, __("application.Unauthorized"));
    }
}
