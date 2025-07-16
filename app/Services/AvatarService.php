<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AvatarService
{
    /**
     * Allowed MIME types for avatar uploads
     */
    const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/jpg', 
        'image/png',
        'image/gif',
        'image/webp'
    ];

    /**
     * Maximum file size in bytes (2MB)
     */
    const MAX_FILE_SIZE = 2097152;

    /**
     * Avatar dimensions
     */
    const AVATAR_SIZE = 256;

    /**
     * Upload and process avatar securely
     *
     * @param UploadedFile $file
     * @param string|null $oldAvatarPath
     * @return string|null
     * @throws \Exception
     */
    public function uploadAvatar(UploadedFile $file, ?string $oldAvatarPath = null): ?string
    {
        // Validate file
        $this->validateFile($file);

        // Create avatars directory if it doesn't exist
        $avatarPath = public_path('storage/avatars');
        if (!file_exists($avatarPath)) {
            mkdir($avatarPath, 0755, true);
        }

        // Generate secure filename
        $filename = $this->generateSecureFilename($file);

        // Delete old avatar if exists
        if ($oldAvatarPath) {
            $this->deleteAvatar($oldAvatarPath);
        }

        // Process and save image
        try {
            $this->processAndSaveImage($file, $avatarPath . '/' . $filename);
            return $filename;
        } catch (\Exception $e) {
            // Clean up on failure
            if (file_exists($avatarPath . '/' . $filename)) {
                unlink($avatarPath . '/' . $filename);
            }
            throw $e;
        }
    }

    /**
     * Validate uploaded file for security
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    private function validateFile(UploadedFile $file): void
    {
        // Check if file was uploaded without errors
        if (!$file->isValid()) {
            throw new \Exception('Invalid file upload');
        }

        // Check file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \Exception('File size too large. Maximum size is 2MB');
        }

        // Check MIME type
        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES)) {
            throw new \Exception('Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed');
        }

        // Additional security: Check if file is actually an image
        $imageInfo = @getimagesize($file->getPathname());
        if ($imageInfo === false) {
            throw new \Exception('File is not a valid image');
        }

        // Check image dimensions (minimum and maximum)
        [$width, $height] = $imageInfo;
        if ($width < 50 || $height < 50) {
            throw new \Exception('Image dimensions too small. Minimum 50x50 pixels');
        }
        if ($width > 2048 || $height > 2048) {
            throw new \Exception('Image dimensions too large. Maximum 2048x2048 pixels');
        }
    }

    /**
     * Generate secure filename
     *
     * @param UploadedFile $file
     * @return string
     */
    private function generateSecureFilename(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Ensure safe extension
        $safeExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($extension, $safeExtensions)) {
            $extension = 'jpg'; // Default to jpg for security
        }

        return 'avatar_' . time() . '_' . Str::random(16) . '.' . $extension;
    }

    /**
     * Process and save image with basic security measures
     * Note: For advanced image processing, consider installing intervention/image
     *
     * @param UploadedFile $file
     * @param string $savePath
     * @throws \Exception
     */
    private function processAndSaveImage(UploadedFile $file, string $savePath): void
    {
        try {
            // Basic secure move - Laravel handles this safely
            if (!$file->move(dirname($savePath), basename($savePath))) {
                throw new \Exception('Failed to save image file');
            }

            // Additional security: Re-encode image to remove potential malicious content
            $this->reencodeImage($savePath);

        } catch (\Exception $e) {
            throw new \Exception('Failed to process image: ' . $e->getMessage());
        }
    }

    /**
     * Re-encode image to remove potential security threats
     *
     * @param string $filePath
     * @throws \Exception
     */
    private function reencodeImage(string $filePath): void
    {
        $imageInfo = getimagesize($filePath);
        if (!$imageInfo) {
            throw new \Exception('Invalid image file');
        }

        $imageType = $imageInfo[2];
        
        try {
            // Create image resource based on type
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $image = imagecreatefromjpeg($filePath);
                    break;
                case IMAGETYPE_PNG:
                    $image = imagecreatefrompng($filePath);
                    break;
                case IMAGETYPE_GIF:
                    $image = imagecreatefromgif($filePath);
                    break;
                case IMAGETYPE_WEBP:
                    $image = imagecreatefromwebp($filePath);
                    break;
                default:
                    throw new \Exception('Unsupported image type');
            }

            if (!$image) {
                throw new \Exception('Failed to create image resource');
            }

            // Re-save the image (this removes any potentially malicious content)
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    imagejpeg($image, $filePath, 85);
                    break;
                case IMAGETYPE_PNG:
                    imagepng($image, $filePath, 8);
                    break;
                case IMAGETYPE_GIF:
                    imagegif($image, $filePath);
                    break;
                case IMAGETYPE_WEBP:
                    imagewebp($image, $filePath, 85);
                    break;
            }

            // Clean up memory
            imagedestroy($image);

        } catch (\Exception $e) {
            throw new \Exception('Failed to re-encode image: ' . $e->getMessage());
        }
    }

    /**
     * Delete avatar file safely
     *
     * @param string $filename
     * @return bool
     */
    public function deleteAvatar(string $filename): bool
    {
        $filePath = public_path('storage/avatars/' . $filename);
        
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        
        return false;
    }

    /**
     * Get avatar URL safely
     *
     * @param string|null $filename
     * @return string
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
     *
     * @param string $filename
     * @return bool
     */
    public function isValidAvatarFilename(string $filename): bool
    {
        // Check pattern: avatar_timestamp_randomstring.extension
        return preg_match('/^avatar_\d+_[a-zA-Z0-9]{16}\.(jpg|jpeg|png|gif|webp)$/', $filename);
    }
}
