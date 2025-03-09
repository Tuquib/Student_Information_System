<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        if (auth()->user()->role !== $role) {
            return auth()->user()->role === 'admin' 
                ? redirect()->route('dashboard')
                : redirect()->route('student.dashboard');
        }

        return $next($request);
    }
} 