<?php

namespace App\Http\Requests\Panel\Menu;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('create-menus');
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'parent_id' => $this->parent_id ?: null,
            'is_active' => $this->has('is_active'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nama_menu' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:master_menus',
            'route_name' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:master_menus,id',
            'urutan' => 'required|integer',
            'is_active' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama_menu.required' => 'Nama menu harus diisi',
            'slug.required' => 'Slug harus diisi',
            'slug.unique' => 'Slug sudah digunakan',
            'urutan.required' => 'Urutan harus diisi',
            'urutan.integer' => 'Urutan harus berupa angka',
        ];
    }
}
