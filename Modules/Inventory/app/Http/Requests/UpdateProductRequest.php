<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Dapatkan ID produk dari route, contoh: /products/{product}
        $productId = $this->route('product')->id;

        return [
            'name' => 'required|string|max:255',
            'sku' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($productId)],
            'barcode' => ['nullable', 'string', 'max:255', Rule::unique('products')->ignore($productId)],
            'description' => 'nullable|string',
            'product_category_id' => 'required|exists:product_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'required|exists:units,id',
            'type' => ['required', Rule::in(['product', 'service'])],
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'track_stock' => 'required|boolean',
            'is_active' => 'required|boolean',
        ];
    }
}
