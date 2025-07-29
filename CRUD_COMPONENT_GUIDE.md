# CRUD Component System

Sistem CRUD yang dapat digunakan kembali untuk Laravel dengan Blade components, Alpine.js, dan DataTables.

## Komponen Utama

### 1. Base Controller (`CrudController`)
- **Lokasi**: `app/Http/Controllers/CrudController.php`
- **Traits**: `CrudableTrait`
- **Fungsi**: Base controller untuk semua operasi CRUD

### 2. CRUD Components
- **Index Component**: `resources/views/components/crud/index.blade.php`
- **Form Component**: `resources/views/components/crud/form.blade.php`
- **DataTable Component**: `resources/views/components/table/datatable.blade.php`

### 3. Generator Command
- **Command**: `php artisan make:crud {name} {module} --fields={json}`
- **Lokasi**: `app/Console/Commands/MakeCrudCommand.php`

## Cara Penggunaan

### 1. Manual Implementation

#### Step 1: Extend CrudController
```php
<?php

namespace Modules\MasterData\Http\Controllers;

use App\Http\Controllers\CrudController;
use App\Models\Product;

class ProductController extends CrudController
{
    protected function initializeCrudConfig()
    {
        $this->setModel(Product::class)
             ->setViewPath('masterdata::products')
             ->setRoutePrefix('master-data.products')
             ->setRelationships(['brand', 'unit', 'category'])
             ->setValidationRules([
                 'name' => 'required|string|max:255',
                 'sku' => 'required|string|max:100|unique:products,sku',
             ]);
    }

    public function getFormFields()
    {
        return [
            [
                'name' => 'name',
                'label' => 'Product Name',
                'type' => 'text',
                'required' => true,
                'col_class' => 'col-md-6',
                'placeholder' => 'Enter product name'
            ],
            [
                'name' => 'sku',
                'label' => 'SKU',
                'type' => 'text',
                'required' => true,
                'col_class' => 'col-md-6',
                'placeholder' => 'Enter SKU'
            ]
        ];
    }
}
```

#### Step 2: Create Index View
```blade
<x-crud.index 
    title="Product Management"
    table-id="products-table"
    :ajax-url="route('master-data.products.index')"
    :create-url="route('master-data.products.create')"
    :columns="['#', 'Name', 'SKU', 'Actions']"
    :show-add="true"
    add-button-text="Add Product"
    modal-title="Product"
>
    {{-- Custom DataTables scripts --}}
</x-crud.index>
```

#### Step 3: Create Form Partial
```blade
@php
    $controller = app(\Modules\MasterData\Http\Controllers\ProductController::class);
    $fields = $controller->getFormFields();
    $item = $product ?? null;
@endphp

<x-crud.form 
    :method="$method ?? 'create'"
    :item="$item"
    :fields="$fields"
    form-id="product-form"
/>
```

### 2. Using Generator Command

#### Generate Complete CRUD
```bash
php artisan make:crud Product MasterData --fields='[
    {
        "name": "name",
        "label": "Product Name", 
        "type": "text",
        "required": true,
        "col_class": "col-md-6",
        "placeholder": "Enter product name",
        "validation": "required|string|max:255"
    },
    {
        "name": "description",
        "label": "Description",
        "type": "textarea", 
        "required": false,
        "col_class": "col-md-12",
        "rows": 3,
        "validation": "nullable|string"
    }
]'
```

## Field Types Supported

### Text Fields
```json
{
    "name": "name",
    "label": "Name",
    "type": "text|email|url|tel|password",
    "required": true,
    "placeholder": "Enter name"
}
```

### Number Fields
```json
{
    "name": "price",
    "label": "Price", 
    "type": "number",
    "required": true,
    "attributes": {"step": "0.01", "min": "0"}
}
```

### Select Fields
```json
{
    "name": "category_id",
    "label": "Category",
    "type": "select",
    "required": true,
    "options": "data_from_controller"
}
```

### Textarea
```json
{
    "name": "description",
    "label": "Description",
    "type": "textarea",
    "rows": 3
}
```

### Checkbox & Radio
```json
{
    "name": "is_active",
    "label": "Status",
    "type": "checkbox",
    "check_label": "Active"
}
```

## Features

### ✅ Modal-based CRUD
- Add, Edit, View dalam modal
- AJAX form submission
- Validation error handling
- Auto table refresh

### ✅ DataTables Integration
- Server-side processing
- Custom action buttons
- Search & pagination
- Export functionality

### ✅ Form Builder
- Dynamic field generation
- Validation rules
- Multiple field types
- Responsive layout

### ✅ Alpine.js Integration
- Reactive modal state
- Form handling
- Error display

### ✅ Generator Command
- Quick CRUD generation
- Custom field configuration
- Controller & view creation

## Contoh Lengkap

Lihat implementasi pada:
- **ProductController**: `Modules/MasterData/app/Http/Controllers/ProductController.php`
- **Product Views**: `Modules/MasterData/resources/views/products/`

## Customization

### Custom DataTables Columns
```php
protected function customizeDataTablesResponse($datatables)
{
    return $datatables
        ->editColumn('price', function ($item) {
            return 'Rp ' . number_format($item->price, 0, ',', '.');
        })
        ->addColumn('status_badge', function ($item) {
            return $item->is_active ? 
                '<span class="badge bg-success">Active</span>' : 
                '<span class="badge bg-secondary">Inactive</span>';
        })
        ->rawColumns(['status_badge']);
}
```

### Custom Validation
```php
protected function getValidationRules($item = null)
{
    $rules = $this->validationRules;
    
    if ($item) {
        $rules['email'] = 'required|email|unique:users,email,' . $item->id;
    }
    
    return $rules;
}
```

### Additional View Data
```php
protected function getAdditionalViewData()
{
    return [
        'categories' => Category::active()->get(),
        'brands' => Brand::active()->get(),
        'units' => Unit::active()->get(),
    ];
}
```

## Best Practices

1. **Gunakan relationships** untuk efisiensi query
2. **Set validation rules** di controller untuk konsistensi
3. **Customize DataTables** sesuai kebutuhan tampilan
4. **Use TomSelect** untuk dropdown yang lebih user-friendly
5. **Handle errors** dengan proper validation dan try-catch

## Debugging

### Check DataTables AJAX
```javascript
// Console log untuk debug
console.log('DataTables AJAX URL:', table.ajax.url());
```

### Check Alpine Component
```javascript
// Get Alpine component
const alpine = Alpine.$data(document.querySelector('[x-data*="tableManager"]'));
console.log('Alpine component:', alpine);
```

### Validation Errors
Controller otomatis menangani validation errors dan menampilkan di form dengan class `invalid-feedback`.

---

**🎉 Sekarang Anda memiliki sistem CRUD yang powerful, reusable, dan modern!**
