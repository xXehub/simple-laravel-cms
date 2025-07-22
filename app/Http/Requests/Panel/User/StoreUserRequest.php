<?php

namespace App\Http\Requests\Panel\User;

use App\Http\Requests\BaseRequest;

class StoreUserRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('create-users');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => $this->textRule(),
            'username' => $this->usernameRule(),
            'email' => $this->emailRule(),
            'password' => $this->passwordRule(),
            'roles' => $this->arrayRule(),
            'roles.*' => 'exists:roles,name'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'name.required' => 'Nama user harus diisi',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);
    }
}
