<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleSubscription
{
    public function handle(Request $request, Closure $next, string $moduleKey)
    {
        $company = auth()->user()->company;
        if ($company && !$company->subscriptions()->where('module_key', $moduleKey)->exists()) {
            abort(403, 'Akses ditolak. Perusahaan Anda tidak berlangganan modul ini.');
        }
        return $next($request);
    }
}
