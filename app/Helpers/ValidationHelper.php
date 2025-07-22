<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class ValidationHelper
{
    /**
     * Check if slug exists in given table
     */
    public static function slugExists(string $slug, string $table, ?int $excludeId = null): bool
    {
        $query = DB::table($table)->where('slug', $slug);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Check if email exists in users table
     */
    public static function emailExists(string $email, ?int $excludeId = null): bool
    {
        $query = DB::table('users')->where('email', $email);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Check if username exists in users table
     */
    public static function usernameExists(string $username, ?int $excludeId = null): bool
    {
        $query = DB::table('users')->where('username', $username);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Check if name exists in given table (for roles, permissions, etc)
     */
    public static function nameExists(string $name, string $table, ?int $excludeId = null): bool
    {
        $query = DB::table($table)->where('name', $name);
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->exists();
    }

    /**
     * Get common validation rules
     */
    public static function commonRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ];
    }

    /**
     * Get slug validation rule for given table
     */
    public static function slugRule(string $table, ?int $excludeId = null): string
    {
        $rule = 'required|string|max:255|unique:' . $table . ',slug';
        
        if ($excludeId) {
            $rule .= ',' . $excludeId;
        }
        
        return $rule;
    }

    /**
     * Get image validation rule
     */
    public static function imageRule(bool $required = false): string
    {
        $rule = $required ? 'required|' : 'nullable|';
        $rule .= 'image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        
        return $rule;
    }

    /**
     * Get name uniqueness rule for given table
     */
    public static function nameRule(string $table, ?int $excludeId = null): string
    {
        $rule = 'required|string|max:255|unique:' . $table . ',name';
        
        if ($excludeId) {
            $rule .= ',' . $excludeId;
        }
        
        return $rule;
    }
}
