<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMenuRequest;
use App\Http\Requests\Admin\UpdateMenuRequest;
use App\Models\MenuItem;
use Spatie\Permission\Models\Permission;

class MenuController extends Controller
{
    public function index()
    {
        $menuGroups = MenuItem::whereNull('parent_id')
            ->with('children')
            ->orderBy('group')->orderBy('order')
            ->get()->groupBy('group');

        return view('admin.menu.index', compact('menuGroups'));
    }

    public function create()
    {
        $permissions = Permission::pluck('name', 'name');
        $parentMenus = MenuItem::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.menu.create', compact('permissions', 'parentMenus'));
    }

    public function store(StoreMenuRequest $request)
    {
        // dd($request);
        MenuItem::create($request->validated());
        alert()->success('Berhasil!', 'Menu item baru telah ditambahkan.');
        return redirect()->route('admin.menu.index');
    }

    public function edit(MenuItem $menu)
    {
        $permissions = Permission::pluck('name', 'name');
        // Pastikan menu tidak bisa menjadi parent dari dirinya sendiri
        $parentMenus = MenuItem::whereNull('parent_id')->where('id', '!=', $menu->id)->orderBy('name')->get();
        return view('admin.menu.edit', compact('menu', 'permissions', 'parentMenus'));
    }

    public function update(UpdateMenuRequest $request, MenuItem $menu)
    {
        $menu->update($request->validated());
        alert()->success('Berhasil!', 'Menu item telah diperbarui.');
        return redirect()->route('admin.menu.index');
    }

    public function destroy(MenuItem $menu)
    {
        if ($menu->children()->exists()) {
            alert()->error('Gagal!', 'Hapus submenu terlebih dahulu sebelum menghapus menu utama.');
            return back();
        }
        $menu->delete();
        alert()->success('Berhasil!', 'Menu item telah dihapus.');
        return redirect()->route('admin.menu.index');
    }
}
