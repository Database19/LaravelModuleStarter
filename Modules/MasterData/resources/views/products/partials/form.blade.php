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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TomSelect for select dropdowns if available
    if (typeof TomSelect !== 'undefined') {
        const selects = document.querySelectorAll('#product-form select');
        selects.forEach(select => {
            if (!select.hasAttribute('data-tomselect-initialized')) {
                new TomSelect(select, {
                    create: false,
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
                select.setAttribute('data-tomselect-initialized', 'true');
            }
        });
    }
});
</script>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div>
            <label for="brand_id" class="block text-sm font-medium text-gray-700 mb-2">
                Brand <span class="text-red-500">*</span>
            </label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white"
                    id="brand_id"
                    @if($method == 'show') disabled @endif
                    name="brand_id"
                    required>
                <option value="" class="text-gray-500">Select Brand</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}"
                            {{ (isset($product) && $product->brand_id == $brand->id) ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
            @error('brand_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-2">
                Unit <span class="text-red-500">*</span>
            </label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white"
                    id="unit_id"
                    @if($method == 'show') disabled @endif
                    name="unit_id"
                    required>
                <option value="" class="text-gray-500">Select Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}"
                            {{ (isset($product) && $product->unit_id == $unit->id) ? 'selected' : '' }}>
                        {{ $unit->name }}
                    </option>
                @endforeach
            </select>
            @error('unit_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="product_category_id" class="block text-sm font-medium text-gray-700 mb-2">
                Category <span class="text-red-500">*</span>
            </label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 bg-white"
                    id="product_category_id"
                    @if($method == 'show') disabled @endif
                    name="product_category_id"
                    required>
                <option value="" class="text-gray-500">Select Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                            {{ (isset($product) && $product->product_category_id == $category->id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('product_category_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                Price <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute left-3 top-2 text-gray-500">$</span>
                <input type="number"
                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                       id="price"
                       @if($method == 'show') disabled @endif
                       name="price"
                       step="0.01"
                       min="0"
                       value="{{ isset($product) ? $product->price : old('price') }}"
                       required
                       placeholder="0.00">
            </div>
            @error('price')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">
                Cost <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <span class="absolute left-3 top-2 text-gray-500">$</span>
                <input type="number"
                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                       id="cost"
                        @if($method == 'show') disabled @endif
                       name="cost"
                       step="0.01"
                       min="0"
                       value="{{ isset($product) ? $product->cost : old('cost') }}"
                       required
                       placeholder="0.00">
            </div>
            @error('cost')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="mt-6">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
            Description
        </label>
        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 resize-vertical"
                  id="description"
                  name="description"
                  rows="4"
                    @if($method == 'show') disabled @endif
                  placeholder="Enter product description...">{{ isset($product) ? $product->description : old('description') }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</form>
