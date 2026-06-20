<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->guard('admin')->check() || !auth()->guard('admin')->user()->isAdmin()) {
            return redirect()->route('admin.login');
        }

        // Set the default guard to admin for this request
        auth()->shouldUse('admin');

        return $next($request);
    }
}
