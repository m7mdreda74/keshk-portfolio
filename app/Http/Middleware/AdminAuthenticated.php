<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        $token     = $request->header('X-Admin-Token');
        $validToken = hash('sha256', config('app.admin_password') . config('app.key'));

        if (!$token || !hash_equals($validToken, $token)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
