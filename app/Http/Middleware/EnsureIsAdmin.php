<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isAdmin()) {
            if ($request->expectsJson()) {
                abort(403, 'Akses khusus Administrator.');
            }

            return redirect()
                ->route('dashboard')
                ->with('error', 'Akses ditolak. Fitur Admin Control hanya dapat diakses oleh Administrator.');
        }

        return $next($request);
    }
}
