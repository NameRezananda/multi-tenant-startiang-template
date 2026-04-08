<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetTenantUrlParameter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($tenant = $request->route()->parameter('tenant')) {
            // Check if tenant is an object (due to Implicit Route Binding), or just the string
            if (is_object($tenant)) {
                URL::defaults(['tenant' => $tenant->slug ?? $tenant->id]);
            } else {
                URL::defaults(['tenant' => $tenant]);
            }
        }

        return $next($request);
    }
}
