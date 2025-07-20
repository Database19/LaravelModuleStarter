<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'route' => 'nullable|string|max:255',
            'permission_name' => 'required|string|exists:permissions,name',
            'icon_svg' => 'required|string',
            'group' => 'required|string|max:255',
            'order' => 'required|integer',
        ];
    }
}
