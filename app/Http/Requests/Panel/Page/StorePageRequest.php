<?php

namespace App\Http\Requests\Panel\Page;

use App\Http\Requests\BaseRequest;

class StorePageRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('create-pages');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => $this->textRule(),
            'slug' => $this->slugRule('pages'),
            'content' => $this->textRule(true, 65535), // for text field
            'template' => $this->textRule(false),
            'is_published' => $this->booleanRule(),
            'meta_title' => $this->textRule(false),
            'meta_description' => $this->textRule(false, 500),
            'sort_order' => $this->numericRule(false, 0),
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'title.required' => 'Judul halaman harus diisi',
            'slug.required' => 'Slug harus diisi',
            'slug.unique' => 'Slug sudah digunakan',
            'content.required' => 'Konten harus diisi',
            'sort_order.integer' => 'Urutan harus berupa angka',
            'sort_order.min' => 'Urutan minimal 0',
        ]);
    }
}
