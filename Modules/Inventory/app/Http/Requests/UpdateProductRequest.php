<?php
namespace Modules\Inventory\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => ['required', 'string', 'max:50', Rule::unique('products')->ignore($this->product->id)],
            'product_category_id' => 'required|exists:product_categories,id',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'unit_of_measurement' => 'required|string|max:20',
            'description' => 'nullable|string',
        ];
    }
}
