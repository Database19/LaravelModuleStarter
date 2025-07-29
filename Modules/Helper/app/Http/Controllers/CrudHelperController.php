<?php

namespace Modules\Helper\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CrudHelperController extends Controller
{
    public function index()
    {
        // Get all modules
        $modules = collect(File::directories(base_path('Modules')))
            ->map(function ($path) {
                return basename($path);
            })
            ->filter(function ($module) {
                return $module !== 'Helper'; // Exclude Helper module itself
            })
            ->sort()
            ->values();

        return view('helper::crud-helper.index', compact('modules'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'model_name' => 'required|string|max:255',
            'module_name' => 'required|string|max:255',
            'fields' => 'required|array|min:1',
            'fields.*.name' => 'required|string',
            'fields.*.type' => 'required|string',
            'fields.*.label' => 'required|string',
            'fields.*.required' => 'boolean',
        ]);

        // dd($request->all()); // Debugging line to check the request data

        try {
            $modelName = Str::studly($request->model_name);
            $moduleName = Str::studly($request->module_name);
            $fields = $request->fields;

            // Generate CRUD using our custom command
            $exitCode = Artisan::call('make:crud', [
                'name' => $modelName,
                'module' => $moduleName,
                '--fields' => json_encode($fields)
            ]);

            if ($exitCode === 0) {
                return response()->json([
                    'success' => true,
                    'message' => "CRUD untuk {$modelName} berhasil dibuat di module {$moduleName}!",
                    'redirect' => route('helper.crud-helper.index')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi error saat generate CRUD. Exit code: ' . $exitCode
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function preview(Request $request)
    {
        $request->validate([
            'model_name' => 'required|string|max:255',
            'module_name' => 'required|string|max:255',
            'fields' => 'required|array|min:1',
        ]);

        $modelName = Str::studly($request->model_name);
        $moduleName = Str::studly($request->module_name);
        $fields = $request->fields;

        // Generate preview data
        $preview = [
            'model' => $modelName,
            'module' => $moduleName,
            'table_name' => Str::snake(Str::plural($modelName)),
            'controller_path' => "Modules/{$moduleName}/app/Http/Controllers/{$modelName}Controller.php",
            'model_path' => "Modules/{$moduleName}/app/Models/{$modelName}.php",
            'migration_path' => "Modules/{$moduleName}/database/migrations/" . date('Y_m_d_His') . "_create_" . Str::snake(Str::plural($modelName)) . "_table.php",
            'views_path' => "Modules/{$moduleName}/resources/views/" . Str::kebab(Str::plural($modelName)),
            'routes' => [
                'index' => "/{$moduleName}/" . Str::kebab(Str::plural($modelName)),
                'create' => "/{$moduleName}/" . Str::kebab(Str::plural($modelName)) . "/create",
                'store' => "/{$moduleName}/" . Str::kebab(Str::plural($modelName)),
                'show' => "/{$moduleName}/" . Str::kebab(Str::plural($modelName)) . "/{id}",
                'edit' => "/{$moduleName}/" . Str::kebab(Str::plural($modelName)) . "/{id}/edit",
                'update' => "/{$moduleName}/" . Str::kebab(Str::plural($modelName)) . "/{id}",
                'destroy' => "/{$moduleName}/" . Str::kebab(Str::plural($modelName)) . "/{id}",
            ],
            'fields' => $fields
        ];

        return response()->json([
            'success' => true,
            'preview' => $preview
        ]);
    }
}
