<?php

namespace App\Http\Requests\Panel\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserAvatarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && (
            auth()->user()->can('update-users') || 
            auth()->id() == $this->route('id')
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'avatar' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:2048', // 2MB max
                'dimensions:min_width=50,min_height=50,max_width=2048,max_height=2048'
            ]
        ];
    }

    /**
     * Get custom validation messages
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'avatar.required' => 'Please select an avatar image.',
            'avatar.image' => 'The file must be an image.',
            'avatar.mimes' => 'Avatar must be a JPEG, PNG, GIF, or WebP image.',
            'avatar.max' => 'Avatar size must not exceed 2MB.',
            'avatar.dimensions' => 'Avatar dimensions must be between 50x50 and 2048x2048 pixels.',
        ];
    }
}
