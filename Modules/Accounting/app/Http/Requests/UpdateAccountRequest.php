<?php
namespace Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // Gunakan $this->route('accounting') untuk mendapatkan model Account dari rute
            'account_code' => ['required', 'string', Rule::unique('accounts')->ignore($this->route('accounting')->id)],
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,revenue,expense',
            'is_active' => 'boolean', // Jangan lupa tambahkan ini jika ada di form
        ];
    }
}
