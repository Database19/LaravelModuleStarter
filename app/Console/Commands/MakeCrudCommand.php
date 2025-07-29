<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeCrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud
                            {name : The name of the model}
                            {module : The module name}
                            {--fields= : Fields configuration in JSON format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate CRUD controller, views, and routes for a model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $module = $this->argument('module');
        $fields = $this->option('fields') ? json_decode($this->option('fields'), true) : [];

        $this->info("Generating CRUD for {$name} in {$module} module...");

        // Generate Controller
        $this->generateController($name, $module, $fields);

        // Generate Views
        $this->generateViews($name, $module, $fields);

        // Generate Routes
        $this->generateRoutes($name, $module);

        $this->info("CRUD generated successfully!");
        $this->info("Don't forget to add the route to your module's route file.");
    }

    protected function generateController($name, $module, $fields)
    {
        $controllerName = "{$name}Controller";
        $modelName = $name;
        $viewPath = strtolower($module) . "::" . Str::snake(Str::plural($name));
        $routePrefix = strtolower($module) . "." . Str::snake(Str::plural($name));

        $stub = File::get(resource_path('stubs/crud-controller.stub'));

        $replacements = [
            '{{namespace}}' => "Modules\\{$module}\\Http\\Controllers",
            '{{controllerName}}' => $controllerName,
            '{{modelName}}' => $modelName,
            '{{modelNamespace}}' => "App\\Models\\{$modelName}",
            '{{viewPath}}' => $viewPath,
            '{{routePrefix}}' => $routePrefix,
            '{{relationships}}' => $this->generateRelationships($fields),
            '{{validationRules}}' => $this->generateValidationRules($fields),
            '{{additionalViewData}}' => $this->generateAdditionalViewData($fields),
            '{{formFields}}' => $this->generateFormFields($fields),
            '{{customDataTablesColumns}}' => $this->generateCustomDataTablesColumns($fields),
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $stub);

        $controllerPath = base_path("Modules/{$module}/app/Http/Controllers/{$controllerName}.php");
        File::put($controllerPath, $content);

        $this->info("Controller created: {$controllerPath}");
    }

    protected function generateViews($name, $module, $fields)
    {
        $viewsPath = base_path("Modules/{$module}/resources/views/" . Str::snake(Str::plural($name)));
        File::makeDirectory($viewsPath, 0755, true, true);
        File::makeDirectory($viewsPath . '/partials', 0755, true, true);

        // Generate index view
        $indexStub = File::get(resource_path('stubs/crud-index.stub'));
        $indexContent = str_replace([
            '{{title}}',
            '{{modelNamePlural}}',
            '{{routePrefix}}',
            '{{columns}}',
            '{{modalTitle}}',
        ], [
            Str::title(Str::plural($name)) . ' Management',
            Str::snake(Str::plural($name)),
            strtolower($module) . "." . Str::snake(Str::plural($name)),
            $this->generateTableColumns($fields),
            Str::title($name),
        ], $indexStub);
        File::put($viewsPath . '/index.blade.php', $indexContent);

        // Generate form partial
        $formStub = File::get(resource_path('stubs/crud-form.stub'));
        $formContent = str_replace([
            '{{controllerNamespace}}',
            '{{formId}}',
        ], [
            "\\Modules\\{$module}\\Http\\Controllers\\{$name}Controller",
            Str::snake($name) . '-form',
        ], $formStub);
        File::put($viewsPath . '/partials/form.blade.php', $formContent);

        // Generate show partial
        $showStub = File::get(resource_path('stubs/crud-show.stub'));
        $showContent = str_replace([
            '{{controllerNamespace}}',
            '{{formId}}',
        ], [
            "\\Modules\\{$module}\\Http\\Controllers\\{$name}Controller",
            Str::snake($name) . '-show',
        ], $showStub);
        File::put($viewsPath . '/partials/show.blade.php', $showContent);

        $this->info("Views created in: {$viewsPath}");
    }

    protected function generateRoutes($name, $module)
    {
        $routeName = Str::snake(Str::plural($name));
        $controllerName = "{$name}Controller";

        $routeContent = "\n// Generated CRUD routes for {$name}\n";
        $routeContent .= "Route::resource('{$routeName}', {$controllerName}::class);\n";

        $this->info("Add this to your {$module} module web.php routes file:");
        $this->info($routeContent);
    }

    protected function generateRelationships($fields)
    {
        if (!$fields) return '[]';

        $relationships = [];
        foreach ($fields as $field) {
            if (isset($field['type']) && $field['type'] === 'relationship') {
                $relationships[] = "'{$field['relation']}'";
            }
        }
        return '[' . implode(', ', $relationships) . ']';
    }

    protected function generateValidationRules($fields)
    {
        if (!$fields) return '[]';

        $rules = [];
        foreach ($fields as $field) {
            if (isset($field['validation'])) {
                $rules[] = "'{$field['name']}' => '{$field['validation']}'";
            }
        }
        return '[' . implode(', ', $rules) . ']';
    }

    protected function generateAdditionalViewData($fields)
    {
        if (!$fields) return '[]';

        $data = [];
        foreach ($fields as $field) {
            if (isset($field['type']) && $field['type'] === 'select' && isset($field['source'])) {
                $data[] = "'{$field['options_key']}' => {$field['source']}";
            }
        }
        return '[' . implode(', ', $data) . ']';
    }

    protected function generateFormFields($fields)
    {
        if (!$fields) return '[]';

        $formFields = [];
        foreach ($fields as $field) {
            $formField = "[\n";
            $formField .= "                'name' => '{$field['name']}',\n";
            $formField .= "                'label' => '{$field['label']}',\n";
            $formField .= "                'type' => '{$field['type']}',\n";

            if (isset($field['required'])) {
                $formField .= "                'required' => " . ($field['required'] ? 'true' : 'false') . ",\n";
            }

            if (isset($field['col_class'])) {
                $formField .= "                'col_class' => '{$field['col_class']}',\n";
            }

            if (isset($field['placeholder'])) {
                $formField .= "                'placeholder' => '{$field['placeholder']}',\n";
            }

            if (isset($field['rows'])) {
                $formField .= "                'rows' => {$field['rows']},\n";
            }

            $formField .= "            ]";
            $formFields[] = $formField;
        }

        return "[\n" . implode(",\n", $formFields) . "\n        ]";
    }

    protected function generateCustomDataTablesColumns($fields)
    {
        // Generate custom DataTables column formatting if needed
        return '';
    }

    protected function generateTableColumns($fields)
    {
        if (!$fields) return "['#', 'Name', 'Actions']";

        $columns = ["'#'"];
        foreach ($fields as $field) {
            if (!isset($field['hidden_in_table']) || !$field['hidden_in_table']) {
                $columns[] = "'{$field['label']}'";
            }
        }
        $columns[] = "'Actions'";
        return '[' . implode(', ', $columns) . ']';
    }
}
