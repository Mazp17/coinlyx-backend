<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->header('Authorization')) {
            return response()->json(['error' => 'Token invalid'], 401);
        }

        $token = explode(' ', $request->header('Authorization'))[1];

        $user = User::where('api_token', $token)->first();
        if (!$user) {
            return response()->json(['error' => 'Token invalid'], 401);
        }

        auth()->setUser($user);

        return $next($request);

    }
}
