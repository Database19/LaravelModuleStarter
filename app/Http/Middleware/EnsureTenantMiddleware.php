<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tenant requirement untuk super admin
        if (Auth::check() && Auth::user()->is_super_admin) {
            // Super admin bisa mengakses tanpa tenant context
            // Tapi jika ada parameter company_id di request, set sebagai tenant
            if ($request->has('company_id')) {
                $tenant = Tenant::find($request->get('company_id'));
                if ($tenant) {
                    $tenant->makeCurrent();
                }
            }
            return $next($request);
        }

        // Skip untuk guest user di halaman public
        if (!Auth::check() && in_array($request->path(), ['/', 'login', 'register', 'password/reset'])) {
            return $next($request);
        }

        // Untuk authenticated user yang bukan super admin
        if (Auth::check() && !Auth::user()->is_super_admin) {
            // Jika belum ada tenant yang aktif
            if (!Tenant::checkCurrent()) {
                // Coba resolve tenant dari request
                $tenantFinder = app(config('multitenancy.tenant_finder'));
                if ($tenantFinder) {
                    $tenant = $tenantFinder->findForRequest($request);
                    if ($tenant) {
                        $tenant->makeCurrent();
                    } else {
                        // Jika user punya company_id, set sebagai tenant
                        if (Auth::user()->company_id) {
                            $tenant = Tenant::find(Auth::user()->company_id);
                            if ($tenant) {
                                $tenant->makeCurrent();
                            }
                        }
                    }
                }
            }
        }

        return $next($request);
    }
}
