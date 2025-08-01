<?php

namespace App\Http\Requests\Panel\Page;

use App\Http\Requests\BaseRequest;

class UpdatePageRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('update-pages');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $pageId = $this->route('id') ?? $this->input('id');
        
        return [
            'title' => $this->textRule(),
            'slug' => $this->slugRule('pages', $pageId),
            'content' => $this->textRule(true, 65535), // for text field
            'template' => $this->textRule(false),
            'is_published' => $this->booleanRule(),
            'status' => 'sometimes|in:draft,published,archived',
            'page_type' => 'sometimes|in:page,post,custom',
            'excerpt' => $this->textRule(false, 1000),
            'featured' => $this->booleanRule(),
            'show_in_menu' => $this->booleanRule(),
            'meta_title' => $this->textRule(false),
            'meta_description' => $this->textRule(false, 500),
            'sort_order' => $this->numericRule(false, 0),
            'parent_id' => 'sometimes|nullable|exists:pages,id|not_in:' . $pageId, // Can't be parent of itself
            'menu_id' => 'sometimes|nullable|exists:master_menus,id',
            'published_at' => 'sometimes|nullable|date',
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
            'status.in' => 'Status harus berupa draft, published, atau archived',
            'page_type.in' => 'Tipe halaman harus berupa page, post, atau custom',
            'parent_id.exists' => 'Halaman parent tidak ditemukan',
            'parent_id.not_in' => 'Halaman tidak bisa menjadi parent dari dirinya sendiri',
            'menu_id.exists' => 'Menu tidak ditemukan',
            'sort_order.integer' => 'Urutan harus berupa angka',
            'sort_order.min' => 'Urutan minimal 0',
            'published_at.date' => 'Tanggal publikasi harus berupa tanggal yang valid',
        ]);
    }
}
