<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ValidationHelper;

abstract class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get common validation rules
     */
    protected function commonRules(): array
    {
        return ValidationHelper::commonRules();
    }

    /**
     * Get slug validation rule for given table
     */
    protected function slugRule(string $table, ?int $excludeId = null): string
    {
        return ValidationHelper::slugRule($table, $excludeId);
    }

    /**
     * Get image validation rule
     */
    protected function imageRule(bool $required = false): string
    {
        return ValidationHelper::imageRule($required);
    }

    /**
     * Get name uniqueness rule for given table
     */
    protected function nameRule(string $table, ?int $excludeId = null): string
    {
        return ValidationHelper::nameRule($table, $excludeId);
    }

    /**
     * Get email rule with uniqueness check
     */
    protected function emailRule(?int $excludeId = null): string
    {
        $rule = 'required|email|max:255|unique:users,email';
        
        if ($excludeId) {
            $rule .= ',' . $excludeId;
        }
        
        return $rule;
    }

    /**
     * Get username rule with uniqueness check
     */
    protected function usernameRule(?int $excludeId = null): string
    {
        $rule = 'required|string|max:255|unique:users,username';
        
        if ($excludeId) {
            $rule .= ',' . $excludeId;
        }
        
        return $rule;
    }

    /**
     * Get password validation rule
     */
    protected function passwordRule(bool $required = true): string
    {
        $rule = $required ? 'required|' : 'nullable|';
        $rule .= 'string|min:8|confirmed';
        
        return $rule;
    }

    /**
     * Get basic text field rule
     */
    protected function textRule(bool $required = true, int $maxLength = 255): string
    {
        $rule = $required ? 'required|' : 'nullable|';
        $rule .= "string|max:{$maxLength}";
        
        return $rule;
    }

    /**
     * Get numeric rule
     */
    protected function numericRule(bool $required = true, int $min = 0): string
    {
        $rule = $required ? 'required|' : 'nullable|';
        $rule .= "integer|min:{$min}";
        
        return $rule;
    }

    /**
     * Get boolean rule
     */
    protected function booleanRule(): string
    {
        return 'nullable|boolean';
    }

    /**
     * Get array rule
     */
    protected function arrayRule(bool $required = false): string
    {
        return $required ? 'required|array' : 'nullable|array';
    }

    /**
     * Custom error messages
     */
    public function messages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'string' => 'The :attribute must be a string.',
            'email' => 'The :attribute must be a valid email address.',
            'unique' => 'The :attribute has already been taken.',
            'confirmed' => 'The :attribute confirmation does not match.',
            'min' => 'The :attribute must be at least :min characters.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'image' => 'The :attribute must be an image file.',
            'mimes' => 'The :attribute must be a file of type: :values.',
        ];
    }
}
