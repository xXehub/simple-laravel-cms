<?php

namespace App\Http\Requests\Panel\Permission;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('update-permissions');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $permissionId = $this->route('id') ?? $this->input('id');
        
        return [
            'name' => 'required|string|max:255|unique:permissions,name,' . $permissionId,
            'group' => 'nullable|string|max:255'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama permission harus diisi',
            'name.unique' => 'Nama permission sudah digunakan',
        ];
    }
}
