<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsPremium
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User|null $user */
        $user = $request->user();

        if (!$user || !$user->isPremium()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Bu içeriğe erişmek için Premium üyelik gereklidir.',
                ], 403);
            }

            return redirect()->route('dashboard')
                ->with('error', 'Bu sayfaya erişmek için Premium üyelik gereklidir.');
        }

        return $next($request);
    }
}
