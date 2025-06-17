<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        \Log::info('Admin middleware check', [
            'user' => $request->user() ? [
                'id' => $request->user()->id,
                'email' => $request->user()->email,
                'role' => $request->user()->role,
                'is_admin' => $request->user()->is_admin,
                'isAdmin()' => $request->user()->isAdmin()
            ] : null
        ]);

        if (!$request->user() || !$request->user()->isAdmin()) {
            \Log::warning('Admin access denied', [
                'user' => $request->user() ? $request->user()->email : null
            ]);
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
