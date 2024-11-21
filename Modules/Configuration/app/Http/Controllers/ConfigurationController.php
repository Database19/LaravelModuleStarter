<?php

namespace Modules\Configuration\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Configuration\Http\Controllers\ModuleSettingController;
use Modules\Configuration\Http\Controllers\RolesController;
use Modules\Configuration\Http\Controllers\PermissionController;
use Modules\Configuration\Http\Controllers\UserManagementController;
use Modules\Configuration\Http\Controllers\SystemSettingController;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('configuration::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('configuration::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        if($id == 'module-setting'){
            $view = app(ModuleSettingController::class);
        }elseif ($id == 'system-setting') {
            $view = app(SystemSettingController::class);
        }elseif ($id == 'user-management') {
            $view = app(UserManagementController::class);
        }elseif ($id == 'roles') {
            $view = app(RolesController::class);
        }elseif ($id == 'permission') {
            $view = app(PermissionController::class);
        }else{
            return view('configuration::index');
        }
        return $view->index();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('configuration::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
