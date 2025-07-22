<?php

namespace App\Http\Requests\Panel\User;

use App\Http\Requests\BaseRequest;

class UpdateUserAvatarRequest extends BaseRequest
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
     */
    public function rules(): array
    {
        return [
            'avatar' => $this->imageRule(true) . '|dimensions:min_width=50,min_height=50,max_width=2048,max_height=2048'
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'avatar.required' => 'Please select an avatar image.',
            'avatar.dimensions' => 'Avatar dimensions must be between 50x50 and 2048x2048 pixels.',
        ]);
    }
}
