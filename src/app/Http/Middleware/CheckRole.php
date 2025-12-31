<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // デバッグ用（後で削除してOK）
        \Log::info('CheckRole middleware', [
            'user_role' => $request->user()->role,
            'allowed_roles' => $roles,
        ]);

        // rolesが空の配列でないか確認
        if (empty($roles)) {
            \Log::warning('CheckRole: No roles provided');
            return $next($request);
        }
        
        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'この操作を行う権限がありません。');
        }

        return $next($request);
    }
}
