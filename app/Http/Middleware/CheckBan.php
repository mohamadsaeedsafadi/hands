<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  
   public function handle($request, Closure $next)
{
    $user = Auth::guard('user_api')->user();

    if ($user && $user->isBanned()) {
        return response()->json([
            'message' => 'Your account is banned',
            'banned_until' => $user->bans()
                ->where('is_active', true)
                ->latest()
                ->value('banned_until')
        ], 403);
    }

    return $next($request);
}
}
