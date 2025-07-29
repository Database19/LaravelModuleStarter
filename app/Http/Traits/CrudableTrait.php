<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

trait CrudableTrait
{
    /**
     * The model instance for CRUD operations
     */
    protected $model;

    /**
     * The view path for CRUD views
     */
    protected $viewPath;

    /**
     * The route prefix for CRUD routes
     */
    protected $routePrefix;

    /**
     * The validation rules for store/update
     */
    protected $validationRules = [];

    /**
     * The validation messages
     */
    protected $validationMessages = [];

    /**
     * The relationships to load
     */
    protected $relationships = [];

    /**
     * The additional data to pass to views
     */
    protected $additionalViewData = [];

    /**
     * Initialize the CRUD trait
     */
    public function initializeCrud()
    {
        if (!$this->model) {
            throw new \Exception('Model not set for CRUD operations');
        }
        if (!$this->viewPath) {
            throw new \Exception('View path not set for CRUD operations');
        }
        if (!$this->routePrefix) {
            throw new \Exception('Route prefix not set for CRUD operations');
        }
    }

    /**
     * Display a listing of the resource for DataTables
     */
    public function getDataTablesData()
    {
        $query = $this->model::query();

        if (!empty($this->relationships)) {
            $query->with($this->relationships);
        }

        $data = $query->latest()->get();

        // Debug logging
        \Illuminate\Support\Facades\Log::info('DataTables Data for ' . $this->model, [
            'count' => $data->count(),
            'first_item' => $data->first(),
            'request_ajax' => request()->ajax(),
            'request_headers' => request()->headers->all()
        ]);

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($item) {
                return $this->generateActionButtons($item);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Generate action buttons for DataTables
     */
    protected function generateActionButtons($item)
    {
        $buttons = '<div class="btn-group" role="group">';

        // View button
        $buttons .= '<button type="button" class="btn btn-sm btn-info me-1 view-btn" data-id="'.$item->id.'" title="View">
            <i class="fas fa-eye"></i>
        </button>';

        // Edit button
        $buttons .= '<button type="button" class="btn btn-sm btn-warning me-1 edit-btn" data-id="'.$item->id.'" title="Edit">
            <i class="fas fa-edit"></i>
        </button>';

        // Delete button
        $buttons .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="'.$item->id.'" title="Delete">
            <i class="fas fa-trash"></i>
        </button>';

        $buttons .= '</div>';

        return $buttons;
    }

    /**
     * Show the form for creating a new resource
     */
    public function createResource()
    {
        $data = $this->getAdditionalViewData();
        $data['method'] = 'create';

        if (request()->ajax()) {
            return view("{$this->viewPath}.partials.form", $data)->render();
        }

        return view("{$this->viewPath}.partials.form", $data);
    }

    /**
     * Store a newly created resource
     */
    public function storeResource(Request $request)
    {
        $this->validateRequest($request);

        // dd($request->all()); // Debugging line, remove in production

        $item = $this->model::create($request->all());

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $this->getSuccessMessage('created'),
                'data' => $item
            ]);
        }

        return redirect()->route("{$this->routePrefix}.index")
            ->with('success', $this->getSuccessMessage('created'));
    }

    /**
     * Display the specified resource
     */
    public function showResource($id)
    {
        $item = $this->findItem($id);
        $data = $this->getAdditionalViewData();
        $data['item'] = $item;
        $data['method'] = 'show';

        if (request()->ajax()) {
            return view("{$this->viewPath}.partials.show", $data)->render();
        }

        return view("{$this->viewPath}.partials.show", $data);
    }

    /**
     * Show the form for editing the specified resource
     */
    public function editResource($id)
    {
        $item = $this->findItem($id);
        $data = $this->getAdditionalViewData();
        $data['item'] = $item;
        $data['method'] = 'edit';

        if (request()->ajax()) {
            return view("{$this->viewPath}.partials.form", $data)->render();
        }

        return view("{$this->viewPath}.partials.form", $data);
    }

    /**
     * Update the specified resource
     */
    public function updateResource(Request $request, $id)
    {
        $item = $this->findItem($id);
        $this->validateRequest($request, $item);

        $item->update($request->all());

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $this->getSuccessMessage('updated'),
                'data' => $item
            ]);
        }

        return redirect()->route("{$this->routePrefix}.index")
            ->with('success', $this->getSuccessMessage('updated'));
    }

    /**
     * Remove the specified resource
     */
    public function destroyResource($id)
    {
        try {
            $item = $this->findItem($id);
            $item->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $this->getSuccessMessage('deleted')
                ]);
            }

            return redirect()->route("{$this->routePrefix}.index")
                ->with('success', $this->getSuccessMessage('deleted'));
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting item: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route("{$this->routePrefix}.index")
                ->with('error', 'Error deleting item: ' . $e->getMessage());
        }
    }

    /**
     * Find item by ID with relationships
     */
    protected function findItem($id)
    {
        $query = $this->model::query();

        if (!empty($this->relationships)) {
            $query->with($this->relationships);
        }

        return $query->findOrFail($id);
    }

    /**
     * Validate the request
     */
    protected function validateRequest(Request $request, $item = null)
    {
        $rules = $this->getValidationRules($item);
        $messages = $this->validationMessages;

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validator->validate();
        }
    }

    /**
     * Get validation rules (can be overridden in child classes)
     */
    protected function getValidationRules($item = null)
    {
        return $this->validationRules;
    }

    /**
     * Get additional view data
     */
    protected function getAdditionalViewData()
    {
        return $this->additionalViewData;
    }

    /**
     * Get success message
     */
    protected function getSuccessMessage($action)
    {
        $modelName = class_basename($this->model);
        return ucfirst($modelName) . " {$action} successfully.";
    }

    /**
     * Set model for CRUD operations
     */
    protected function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Set view path for CRUD views
     */
    protected function setViewPath($path)
    {
        $this->viewPath = $path;
        return $this;
    }

    /**
     * Set route prefix for CRUD routes
     */
    protected function setRoutePrefix($prefix)
    {
        $this->routePrefix = $prefix;
        return $this;
    }

    /**
     * Set validation rules
     */
    protected function setValidationRules($rules)
    {
        $this->validationRules = $rules;
        return $this;
    }

    /**
     * Set relationships to load
     */
    protected function setRelationships($relationships)
    {
        $this->relationships = $relationships;
        return $this;
    }

    /**
     * Set additional view data
     */
    protected function setAdditionalViewData($data)
    {
        $this->additionalViewData = $data;
        return $this;
    }
}
