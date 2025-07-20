<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->paginate(8);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('-', $permission->name)[0]; // Mengelompokkan berdasarkan modul (e.g., 'manage')
        });
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Peran berhasil dibuat.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('-', $permission->name)[0];
        });
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Peran berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        // Mencegah penghapusan peran Admin
        if ($role->name === 'Admin') {
            return redirect()->route('admin.roles.index')->with('error', 'Peran Admin tidak dapat dihapus.');
        }

        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'Peran berhasil dihapus.');
    }
}
