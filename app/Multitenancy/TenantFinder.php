<?php

namespace App\Multitenancy;

use App\Models\Company;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\TenantFinder\TenantFinder as BaseTenantFinder;

class TenantFinder extends BaseTenantFinder
{
    public function findForRequest(Request $request): ?Tenant
    {
        $host = $request->getHost();

        // Cari company berdasarkan domain
        $company = Company::where('domain', $host)->first();

        if ($company) {
            // Return tenant berdasarkan company_id
            return Tenant::find($company->id);
        }

        // Jika tidak ditemukan, coba cari berdasarkan subdomain
        // Misal: tenant1.erp.test -> tenant1
        $subdomain = $this->extractSubdomain($host);
        if ($subdomain) {
            $company = Company::where('domain', 'like', $subdomain . '.%')->first();
            if ($company) {
                return Tenant::find($company->id);
            }
        }

        return null;
    }

    protected function extractSubdomain(string $host): ?string
    {
        $parts = explode('.', $host);

        // Jika ada minimal 3 bagian (subdomain.domain.tld)
        if (count($parts) >= 3) {
            return $parts[0];
        }

        return null;
    }
}
