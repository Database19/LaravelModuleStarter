<?php

namespace App\Http\Controllers;

use App\Http\Traits\CrudableTrait;
use Illuminate\Http\Request;

abstract class CrudController extends Controller
{
    use CrudableTrait;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initializeCrudConfig();
        $this->initializeCrud();
    }

    /**
     * Initialize CRUD configuration (must be implemented by child classes)
     */
    abstract protected function initializeCrudConfig();

    /**
     * Display a listing of the resource
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->getDataTablesData();
        }

        return view("{$this->viewPath}.index");
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        return $this->createResource();
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        return $this->storeResource($request);
    }

    /**
     * Display the specified resource
     */
    public function show($id)
    {
        return $this->showResource($id);
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit($id)
    {
        return $this->editResource($id);
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, $id)
    {
        return $this->updateResource($request, $id);
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy($id)
    {
        return $this->destroyResource($id);
    }

    /**
     * Get DataTables columns configuration (can be overridden)
     */
    protected function getDataTablesColumns()
    {
        return [];
    }

    /**
     * Customize DataTables response (can be overridden)
     */
    protected function customizeDataTablesResponse($datatables)
    {
        return $datatables;
    }

    /**
     * Override getDataTablesData to allow customization
     */
    public function getDataTablesData()
    {
        $query = $this->model::query();

        if (!empty($this->relationships)) {
            $query->with($this->relationships);
        }

        $data = $query->latest()->get();

        $datatables = datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return $this->generateActionButtons($item);
            })
            ->rawColumns(['action']);

        // Allow child classes to customize the response
        $datatables = $this->customizeDataTablesResponse($datatables);

        return $datatables->make(true);
    }
}
