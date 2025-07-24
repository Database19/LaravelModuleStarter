<?php

namespace Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return auth()->user()->can('create-products');
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'barcode' => 'nullable|string|max:255|unique:products,barcode',
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

    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'name.max' => 'Nama produk tidak boleh lebih dari 255 karakter.',

            'sku.required' => 'SKU (Stock Keeping Unit) wajib diisi.',
            'sku.unique' => 'SKU ini sudah digunakan oleh produk lain.',

            'barcode.unique' => 'Barcode ini sudah digunakan oleh produk lain.',

            'product_category_id.required' => 'Kategori produk wajib dipilih.',
            'product_category_id.exists' => 'Kategori produk yang dipilih tidak valid.',

            'brand_id.exists' => 'Brand yang dipilih tidak valid.',
            'unit_id.required' => 'Satuan unit wajib dipilih.',
            'unit_id.exists' => 'Satuan unit yang dipilih tidak valid.',

            'type.required' => 'Tipe produk wajib dipilih.',
            'type.in' => 'Tipe produk harus berupa "product" atau "service".',

            'price.required' => 'Harga jual wajib diisi.',
            'price.numeric' => 'Harga jual harus berupa angka.',
            'price.min' => 'Harga jual tidak boleh kurang dari 0.',

            'cost.required' => 'Harga modal (cost) wajib diisi.',
            'cost.numeric' => 'Harga modal harus berupa angka.',
            'cost.min' => 'Harga modal tidak boleh kurang dari 0.',

            'quantity.required' => 'Kuantitas awal wajib diisi.',
            'quantity.integer' => 'Kuantitas harus berupa bilangan bulat.',
            'quantity.min' => 'Kuantitas tidak boleh kurang dari 0.',

            'min_stock.required' => 'Stok minimum wajib diisi.',
            'min_stock.integer' => 'Stok minimum harus berupa bilangan bulat.',
            'min_stock.min' => 'Stok minimum tidak boleh kurang dari 0.',

            'track_stock.required' => 'Opsi pelacakan stok wajib dipilih.',
            'is_active.required' => 'Status produk wajib dipilih.',
        ];
    }
}
