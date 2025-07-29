@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="crudHelper()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">CRUD Helper Generator</h1>
                    <p class="mt-2 text-gray-600">Generate CRUD lengkap dengan design modern dan views yang bagus</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-magic mr-2"></i>
                        Auto Generator
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Generator -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Generator Configuration</h2>

                    <form @submit.prevent="generateCrud" class="space-y-6">
                        <!-- Model & Module Info -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Model Name</label>
                                <input
                                    type="text"
                                    x-model="form.model_name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="e.g., Product, Category, User"
                                    required
                                >
                                <p class="mt-1 text-xs text-gray-500">Singular form, PascalCase</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Module</label>
                                <select
                                    x-model="form.module_name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    required
                                >
                                    <option value="">Select Module...</option>
                                    @foreach($modules as $module)
                                        <option value="{{ $module }}">{{ $module }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Fields Configuration -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <label class="block text-sm font-medium text-gray-700">Model Fields</label>
                                <button
                                    type="button"
                                    @click="addField"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium"
                                >
                                    <i class="fas fa-plus mr-2"></i>Add Field
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(field, index) in form.fields" :key="index">
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Field Name</label>
                                                <input
                                                    type="text"
                                                    x-model="field.name"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-1 focus:ring-blue-500"
                                                    placeholder="e.g., name, email"
                                                    required
                                                >
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Label</label>
                                                <input
                                                    type="text"
                                                    x-model="field.label"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-1 focus:ring-blue-500"
                                                    placeholder="e.g., Full Name"
                                                    required
                                                >
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Type</label>
                                                <select
                                                    x-model="field.type"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-1 focus:ring-blue-500"
                                                >
                                                    <option value="string">String</option>
                                                    <option value="text">Text</option>
                                                    <option value="integer">Integer</option>
                                                    <option value="decimal">Decimal</option>
                                                    <option value="boolean">Boolean</option>
                                                    <option value="date">Date</option>
                                                    <option value="datetime">DateTime</option>
                                                    <option value="email">Email</option>
                                                    <option value="url">URL</option>
                                                    <option value="select">Select</option>
                                                    <option value="file">File</option>
                                                    <option value="image">Image</option>
                                                </select>
                                            </div>

                                            <div class="flex items-center justify-between">
                                                <label class="flex items-center text-xs text-gray-600">
                                                    <input
                                                        type="checkbox"
                                                        x-model="field.required"
                                                        class="mr-2 text-blue-600 focus:ring-blue-500"
                                                    >
                                                    Required
                                                </label>
                                                <button
                                                    type="button"
                                                    @click="removeField(index)"
                                                    class="text-red-600 hover:text-red-800 transition-colors"
                                                    x-show="form.fields.length > 1"
                                                >
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Additional options for select field -->
                                        <div x-show="field.type === 'select'" class="mt-3">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Options (comma separated)</label>
                                            <input
                                                type="text"
                                                x-model="field.options"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-1 focus:ring-blue-500"
                                                placeholder="e.g., Active,Inactive or 1:Active,0:Inactive"
                                            >
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <button
                                type="button"
                                @click="previewCrud"
                                class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors font-medium"
                            >
                                <i class="fas fa-eye mr-2"></i>Preview
                            </button>

                            <button
                                type="submit"
                                :disabled="loading"
                                class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-colors font-medium disabled:opacity-50"
                            >
                                <span x-show="!loading">
                                    <i class="fas fa-magic mr-2"></i>Generate CRUD
                                </span>
                                <span x-show="loading">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>Generating...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info & Templates -->
            <div class="space-y-6">
                <!-- Quick Templates -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Templates</h3>

                    <div class="space-y-3">
                        <button
                            @click="applyTemplate('product')"
                            class="w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            <div class="font-medium text-gray-900">Product</div>
                            <div class="text-sm text-gray-500">name, description, price, category</div>
                        </button>

                        <button
                            @click="applyTemplate('user')"
                            class="w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            <div class="font-medium text-gray-900">User</div>
                            <div class="text-sm text-gray-500">name, email, phone, role</div>
                        </button>

                        <button
                            @click="applyTemplate('category')"
                            class="w-full text-left p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            <div class="font-medium text-gray-900">Category</div>
                            <div class="text-sm text-gray-500">name, description, status</div>
                        </button>
                    </div>
                </div>

                <!-- What will be generated -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-list-check mr-2 text-green-600"></i>
                        What will be generated
                    </h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Controller with CRUD methods
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Model with relationships
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Migration file
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Form validation requests
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Beautiful Blade views
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            DataTables integration
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            Alpine.js modal forms
                        </div>
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            TailwindCSS styling
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div x-show="showPreview"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
         style="display: none;">
        <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900">CRUD Preview</h3>
                    <button @click="showPreview = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="p-6" x-show="preview">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Files to be created:</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-file-code text-blue-500 mr-2"></i>
                                <span x-text="preview?.controller_path"></span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-file-code text-green-500 mr-2"></i>
                                <span x-text="preview?.model_path"></span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-database text-orange-500 mr-2"></i>
                                <span x-text="preview?.migration_path"></span>
                            </div>
                            <div class="flex items-center text-gray-700">
                                <i class="fas fa-folder text-purple-500 mr-2"></i>
                                <span x-text="preview?.views_path + '/*.blade.php'"></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Routes:</h4>
                        <div class="space-y-1 text-sm text-gray-600">
                            <template x-for="(route, name) in preview?.routes || {}" :key="name">
                                <div class="flex">
                                    <span class="w-16 text-xs font-medium text-gray-500 uppercase" x-text="name"></span>
                                    <span x-text="route"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="font-semibold text-gray-900 mb-3">Database Fields:</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Field</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Required</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="field in preview?.fields || []" :key="field.name">
                                    <tr>
                                        <td class="px-4 py-2 text-sm font-medium text-gray-900" x-text="field.name"></td>
                                        <td class="px-4 py-2 text-sm text-gray-500" x-text="field.type"></td>
                                        <td class="px-4 py-2 text-sm text-gray-500">
                                            <span x-show="field.required" class="text-red-600">Yes</span>
                                            <span x-show="!field.required" class="text-gray-400">No</span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function crudHelper() {
    return {
        loading: false,
        showPreview: false,
        preview: null,
        form: {
            model_name: '',
            module_name: '',
            fields: [
                { name: 'name', label: 'Name', type: 'string', required: true }
            ]
        },

        addField() {
            this.form.fields.push({
                name: '',
                label: '',
                type: 'string',
                required: false
            });
        },

        removeField(index) {
            this.form.fields.splice(index, 1);
        },

        applyTemplate(template) {
            const templates = {
                product: {
                    model_name: 'Product',
                    module_name: 'MasterData',
                    fields: [
                        { name: 'name', label: 'Product Name', type: 'string', required: true },
                        { name: 'description', label: 'Description', type: 'text', required: false },
                        { name: 'price', label: 'Price', type: 'decimal', required: true },
                        { name: 'category', label: 'Category', type: 'string', required: true },
                        { name: 'status', label: 'Status', type: 'select', required: true, options: 'active,inactive' }
                    ]
                },
                user: {
                    model_name: 'Employee',
                    module_name: 'HumanResource',
                    fields: [
                        { name: 'name', label: 'Full Name', type: 'string', required: true },
                        { name: 'email', label: 'Email', type: 'email', required: true },
                        { name: 'phone', label: 'Phone Number', type: 'string', required: false },
                        { name: 'role', label: 'Role', type: 'select', required: true, options: 'admin,staff,manager' },
                        { name: 'hire_date', label: 'Hire Date', type: 'date', required: true }
                    ]
                },
                category: {
                    model_name: 'Category',
                    module_name: 'MasterData',
                    fields: [
                        { name: 'name', label: 'Category Name', type: 'string', required: true },
                        { name: 'description', label: 'Description', type: 'text', required: false },
                        { name: 'status', label: 'Status', type: 'select', required: true, options: 'active,inactive' }
                    ]
                }
            };

            if (templates[template]) {
                this.form = { ...templates[template] };
            }
        },

        async previewCrud() {
            try {
                const response = await fetch('{{ route("helper.crud-helper.preview") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.form)
                });

                const data = await response.json();

                if (data.success) {
                    this.preview = data.preview;
                    this.showPreview = true;
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        },

        async generateCrud() {
            this.loading = true;

            try {
                const response = await fetch('{{ route("helper.crud-helper.generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-                     console.log("Data:", data);TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                  console.error(("Error: ", rror);   },
                    bod1y: JSON.stringify(this.form)
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    if (data.redirect) {
                        window.location.reload();
                    }
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection
