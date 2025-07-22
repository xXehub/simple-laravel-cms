<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class FileHelper
{
    /**
     * Upload image file with validation and optimization
     */
    public static function uploadImage(UploadedFile $file, string $path, ?string $oldFile = null, int $maxWidth = 800): string
    {
        // Create directory if not exists
        $fullPath = public_path("storage/{$path}");
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        // Generate unique filename
        $filename = self::generateFilename($file);

        // Save and optimize image
        $image = Image::read($file);
        
        // Resize if width exceeds max width
        if ($image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }

        // Save to storage
        $image->save($fullPath . '/' . $filename);

        // Delete old file if exists
        if ($oldFile) {
            self::deleteFile($path . '/' . $oldFile);
        }

        return $filename;
    }

    /**
     * Delete file from storage
     */
    public static function deleteFile(string $filePath): bool
    {
        $fullPath = public_path("storage/{$filePath}");
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        
        return false;
    }

    /**
     * Generate unique filename with timestamp and random string
     */
    public static function generateFilename(UploadedFile $file, ?string $prefix = null): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('YmdHis');
        $random = Str::random(8);
        
        $filename = $timestamp . '_' . $random;
        
        if ($prefix) {
            $filename = $prefix . '_' . $filename;
        }
        
        return $filename . '.' . $extension;
    }

    /**
     * Validate image file
     */
    public static function validateImage(UploadedFile $file, int $maxSize = 2048): array
    {
        $errors = [];
        
        // Check file type
        $allowedTypes = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
        if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedTypes)) {
            $errors[] = 'File must be an image (jpeg, jpg, png, gif, webp)';
        }
        
        // Check file size (in KB)
        if ($file->getSize() > ($maxSize * 1024)) {
            $errors[] = "File size must not exceed {$maxSize}KB";
        }
        
        return $errors;
    }

    /**
     * Get file size in human readable format
     */
    public static function humanFileSize(int $bytes, int $decimals = 2): string
    {
        $size = ['B', 'KB', 'MB', 'GB', 'TB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
    }

    /**
     * Check if file exists in storage
     */
    public static function exists(string $filePath): bool
    {
        return file_exists(public_path("storage/{$filePath}"));
    }

    /**
     * Get file URL
     */
    public static function url(string $filePath): string
    {
        return asset("storage/{$filePath}");
    }
}
