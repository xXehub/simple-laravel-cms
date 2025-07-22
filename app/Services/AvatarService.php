<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use App\Helpers\FileHelper;

class AvatarService
{
    /**
     * Avatar dimensions
     */
    const AVATAR_SIZE = 256;

    /**
     * Upload and process avatar securely
     */
    public function uploadAvatar(UploadedFile $file, ?string $oldAvatarPath = null): ?string
    {
        try {
            // Use FileHelper for secure upload with avatar-specific settings
            return FileHelper::uploadImage($file, 'avatars', $oldAvatarPath, 256);

        } catch (\Exception $e) {
            throw new \Exception('Failed to upload avatar: ' . $e->getMessage());
        }
    }

    /**
     * Delete avatar file safely
     */
    public function deleteAvatar(string $filename): bool
    {
        return FileHelper::deleteFile('avatars/' . $filename);
    }

    /**
     * Get avatar URL safely
     */
    public function getAvatarUrl(?string $filename): string
    {
        if (!$filename) {
            return asset('static/avatars/default.jpg');
        }

        $filePath = public_path('storage/avatars/' . $filename);
        
        if (file_exists($filePath)) {
            return asset('storage/avatars/' . $filename);
        }

        return asset('static/avatars/default.jpg');
    }

    /**
     * Validate avatar filename for security
     */
    public function isValidAvatarFilename(string $filename): bool
    {
        // Check pattern: avatar_timestamp_randomstring.extension
        return preg_match('/^avatar_\d+_[a-zA-Z0-9]{16}\.(jpg|jpeg|png|gif|webp)$/', $filename);
    }
}
