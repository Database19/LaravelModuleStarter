<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            // dd(Auth::user());
            if (!Auth::user()->is_super_admin) {
                abort(403, 'Access denied. Super Admin only.');
            }
            return $next($request);
        });
    }

    /**
     * Display all companies
     */
    public function companies()
    {
        $companies = Company::withCount(['users'])->paginate(15);
        return view('superadmin.companies.index', compact('companies'));
    }

    /**
     * Show company switcher
     */
    public function switchCompany()
    {
        $companies = Company::all();
        $currentCompany = Auth::user()->company;
        return view('superadmin.switch-company', compact('companies', 'currentCompany'));
    }

    /**
     * Switch to different company
     */
    public function switchToCompany(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);

        $company = Company::findOrFail($request->company_id);

        // Update super admin's current company context
        User::where('id', Auth::id())->update([
            'company_id' => $company->id
        ]);

        // Set tenant context
        $company->makeCurrent();

        return redirect()->route('home')->with('success', 'Switched to company: ' . $company->name);
    }

    /**
     * Display global users across all companies
     */
    public function globalUsers()
    {
        $users = User::with(['company', 'roles'])
                    ->paginate(15);
        return view('superadmin.users.index', compact('users'));
    }

    /**
     * System monitoring dashboard
     */
    public function monitor()
    {
        $stats = [
            'total_companies' => Company::count(),
            'total_users' => User::count(),
            'super_admins' => User::where('is_super_admin', true)->count(),
            'active_users' => User::where('employment_status', 'active')->count(),
        ];

        $recentUsers = User::with(['company', 'roles'])
                          ->latest()
                          ->limit(10)
                          ->get();

        return view('superadmin.monitor.index', compact('stats', 'recentUsers'));
    }
}
