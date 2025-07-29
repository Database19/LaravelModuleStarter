<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Multitenancy\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminCompanySwitcher
{
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya untuk super admin
        if (!Auth::check() || !Auth::user()->is_super_admin) {
            return $next($request);
        }

        // Jika ada parameter switch_company, switch ke company tersebut
        if ($request->has('switch_company')) {
            $companyId = $request->get('switch_company');
            $tenant = Tenant::find($companyId);

            if ($tenant) {
                $tenant->makeCurrent();

                // Store di session untuk persistence
                session(['super_admin_active_company' => $companyId]);

                // Redirect tanpa parameter switch_company
                return redirect($request->url());
            }
        }

        // Restore company dari session jika ada
        if (session('super_admin_active_company') && !Tenant::checkCurrent()) {
            $tenant = Tenant::find(session('super_admin_active_company'));
            if ($tenant) {
                $tenant->makeCurrent();
            }
        }

        return $next($request);
    }
}
