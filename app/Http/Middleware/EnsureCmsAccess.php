<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCmsAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAdminOrEditor()) {
            abort(403, 'Bu alana erişim yetkiniz yok.');
        }

        return $next($request);
    }
}
