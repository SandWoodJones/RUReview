<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    use ApiResponse;

    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()?->is_admin) {
            return $this->error('Acesso restrito a administradores', 403);
        }

        return $next($request);
    }
}
