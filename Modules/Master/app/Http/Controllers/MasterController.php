<?php

namespace Modules\Master\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('master::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master::create');
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
        if($id == 'material'){
            $view = app(MaterialController::class);
        }elseif ($id == 'product') {
            $view = app(ProductController::class);
        }elseif ($id == 'warehouse') {
            $view = app(WarehouseController::class);
        }elseif ($id == 'supplier') {
            $view = app(SupplierController::class);
        }elseif ($id == 'workcenter') {
            $view = app(WorkCenterController::class);
        }elseif ($id == 'customer') {
            $view = app(CustomerController::class);
        }elseif ($id == 'machine') {
            $view = app(MachineController::class);
        }elseif ($id == 'bom') {
            $view = app(BomController::class);
        }else{
            return view('master::index');
        }
        return $view->index();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('master::edit');
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
