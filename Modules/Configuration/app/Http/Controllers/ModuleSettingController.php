<?php

namespace Modules\Configuration\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\SubMenu;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ModuleSettingController extends Controller
{

    public $routeNames = 'configuration.module-setting.index';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keyword = request()->input('search');
        
        $data = Menu::where('is_active', 1)
            ->when($keyword, function ($query, $keyword) {
                return $query->where('name', 'like', '%' . $keyword . '%');
            })
            ->paginate(10);

        $data->load('submenus');

        return view('configuration::module-setting.index', compact('data', 'keyword'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permission = Permission::all();

        return view('configuration::module-setting.create', compact(['permission']));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Log untuk menandai awal proses
        Log::info('Mulai proses penyimpanan module.', ['request' => $request->all()]);

        // Gunakan transaksi database untuk memastikan atomicity
        DB::beginTransaction();

        try {
            // Step 1: Buat menu utama
            $menu = Menu::create([
                'name' => $request->name,
                'description' => $request->description,
                'icons' => '',
                'route' => str_replace(' ', '-', strtolower(trim($request->name))),
                'permission' => 'manage-user',
                'is_active' => 0,
            ]);

            Log::info('Menu utama berhasil dibuat.', ['menu' => $menu]);

            // Step 2: Buat submenus
            foreach ($request->submodules as $subMenuData) {
                $subMenu = SubMenu::create([
                    'parent_id' => $menu->id,
                    'name' => $subMenuData['name'],
                    'description' => $subMenuData['description'],
                    'icons' => '',
                    'route' => str_replace(' ', '-', strtolower(trim($subMenuData['name']))),
                    'is_active' => 0,
                ]);
                Log::info('Submenu berhasil dibuat.', ['submenu' => $subMenu]);
            }

            // Step 3: Buat modul menggunakan Artisan
            $moduleName = str_replace(' ', '', trim($request->name));
            $newModule = Artisan::call('module:make ' . $moduleName);

            if ($newModule !== 0) {
                throw new \Exception("Gagal membuat module: $moduleName");
            }
            Log::info('Module berhasil dibuat.', ['module_name' => $moduleName]);

            // Step 4: Buat controllers untuk submodules
            foreach ($request->submodules as $sub) {
                $controllerName = str_replace(' ', '', trim($sub['name']));
                $viewName = str_replace(' ', '-', trim($sub['name']));
                $controllerCreation = Artisan::call("module:make-controller $controllerName $moduleName");

                if ($controllerCreation !== 0) {
                    throw new \Exception("Gagal membuat controller: $controllerName di module: $moduleName");
                }
                Log::info('Controller berhasil dibuat.', ['controller' => $controllerName, 'module' => $moduleName]);

                $this->createFileInModule($moduleName, 'views/'.$viewName.'/index.blade.php', "@extends('layouts.app')

                @section('content')
                    <div class='container'>
                        <div class='row justify-content-center'>
                            <div class='col-md-12 text-center'>
                                <h1>{{$viewName}}</h1>
                            </div>
                        </div>
                    </div>
                @endsection");
            }

            // Step 5: Buat file view di modul
            $this->createFileInModule($moduleName, 'views/index.blade.php', "@extends('layouts.app')

    @section('content')
        <div class='container'>
            <div class='row justify-content-center'>
                <div class='col-md-12'>
                    <x-sub-menu-card />
                </div>
            </div>
        </div>
    @endsection");

            Log::info('File view berhasil dibuat.', ['module' => $moduleName]);

            // Commit jika semua berhasil
            DB::commit();

            // Tampilkan pesan sukses
            Alert::success('Sukses', 'Module berhasil tambahkan!');
            return redirect()->route($this->routeNames);
        } catch (\Exception $e) {
            // Rollback jika ada kegagalan
            DB::rollBack();

            // Log kesalahan
            Log::error('Proses penyimpanan module gagal.', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Tampilkan pesan error ke user
            Alert::error('Error Title', 'Proses gagal: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('configuration::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $menu = Menu::with('SubMenus')->findOrFail($id);
        if(!$menu){
            Alert::info('Tidak Ditemukan', 'Module Tidak ditemukan !');
        }

        return view('configuration::module-setting.edit', compact(['menu']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Log awal proses
        Log::info('Mulai proses update module.', ['request' => $request->all(), 'menu_id' => $id]);

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'submodules.*.name' => 'required|string|max:255',
            'submodules.*.description' => 'required|string|max:255',
        ]);

        // Transaksi untuk memastikan atomicity

        // dd($request->all());
        DB::beginTransaction();

        try {
            // Update Menu utama
            $menu = Menu::findOrFail($id);
            $routeSub = explode('.', $menu->route);
            $menu->update([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            Log::info('Menu utama berhasil diperbarui.', ['menu' => $menu]);

            // Hapus submodules lama
            SubMenu::where('parent_id', $menu->id)->delete();
            Log::info('Submodules lama dihapus.', ['menu_id' => $menu->id]);

            // Tambahkan submodules baru
            foreach ($request->submodules as $subModuleData) {
                $subMenu = SubMenu::create([
                    'parent_id' => $menu->id,
                    'name' => $subModuleData['name'],
                    'description' => $subModuleData['description'],
                    'route' => $routeSub[0].'.'.str_replace(' ', '-', strtolower(trim($subModuleData['name']))).'.index',
                    'is_active' => $subModuleData['is_active'] ?? false ? 1 : 0,
                ]);
                Log::info('Submodule berhasil ditambahkan.', ['submenu' => $subMenu]);
            }

            DB::commit();

            // Berikan notifikasi sukses
            Alert::success('Sukses', 'Module berhasil diperbarui!');
            return redirect()->route($this->routeNames);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log error
            Log::error('Proses update module gagal.', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
