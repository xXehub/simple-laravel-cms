<?php

namespace App\Http\Requests\Panel\Menu;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('update-menus');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $menuId = $this->route('id') ?? $this->input('id');
        
        return [
            'nama_menu' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:master_menus,slug,' . $menuId,
            'route_name' => 'nullable|string|max:255',
            'route_type' => 'nullable|string|in:public,admin,api',
            'controller_class' => 'nullable|string|max:255',
            'view_path' => 'nullable|string|max:255',
            'middleware_list' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:255',
            'parent_id' => [
                'nullable',
                'exists:master_menus,id',
                function ($attribute, $value, $fail) use ($menuId) {
                    // Prevent circular reference
                    if ($value && $value == $menuId) {
                        $fail('Menu cannot be parent of itself.');
                    }
                },
            ],
            'urutan' => 'required|integer',
            'is_active' => 'boolean',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Convert empty string to null for parent_id
        if ($this->input('parent_id') === '') {
            $this->merge(['parent_id' => null]);
        }
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
