<?php

namespace App\Services;

use Cloudinary\Cloudinary as CloudinarySDK;
use Cloudinary\Api\Upload\UploadApi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected $cloudinary;
    protected $uploadApi;

    public function __construct()
    {
        $this->cloudinary = new CloudinarySDK([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key' => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
        ]);

        $this->uploadApi = $this->cloudinary->uploadApi();
    }

    /**
     * Upload image to Cloudinary
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param array $options
     * @return array|null
     */
    public function uploadImage(UploadedFile $file, string $folder = null, array $options = [])
    {
        try {
            $folder = $folder ?? config('cloudinary.folder', 'lana_shop');
            
            $uploadOptions = array_merge([
                'folder' => $folder,
                'resource_type' => 'image',
                'transformation' => [
                    'width' => 800,
                    'height' => 800,
                    'crop' => 'fill',
                    'quality' => 'auto',
                ],
            ], $options);

            $result = $this->uploadApi->upload($file->getRealPath(), $uploadOptions);

            return [
                'public_id' => $result['public_id'],
                'url' => $result['secure_url'],
                'width' => $result['width'],
                'height' => $result['height'],
                'format' => $result['format'],
                'bytes' => $result['bytes'],
            ];
        } catch (\Exception $e) {
            Log::error('Cloudinary upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete image from Cloudinary
     *
     * @param string $imageUrl
     * @return bool
     */
    public function deleteImage(string $imageUrl): bool
    {
        try {
            // Extract public_id from the image URL
            $publicId = $this->extractPublicIdFromUrl($imageUrl);
            
            if (!$publicId) {
                Log::error('Could not extract public_id from image URL: ' . $imageUrl);
                return false;
            }
            Log::info('Deleting image from Cloudinary: ' . $publicId);
            $this->uploadApi->destroy($publicId);
            return true;
        } catch (\Exception $e) {
            Log::error('Cloudinary delete failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Extract public_id from Cloudinary URL
     *
     * @param string $imageUrl
     * @return string|null
     */
    private function extractPublicIdFromUrl(string $imageUrl): ?string
    {
        // Parse the URL to extract the public_id
        $urlParts = parse_url($imageUrl);
        
        if (!$urlParts || !isset($urlParts['path'])) {
            return null;
        }
        
        // Remove leading slash and file extension
        $path = ltrim($urlParts['path'], '/');
        
        // Remove version if present (e.g., v1234567890/)
        $path = preg_replace('/^v\d+\//', '', $path);
        
        // Remove file extension
        $path = preg_replace('/\.(jpg|jpeg|png|gif|webp)$/i', '', $path);
        // da9yv4mbf/image/upload/v1751008138/lana_shop/products/kzvbwicxhxzvpabimdsm chỉ lấy từ lana_shop/products/kzvbwicxhxzvpabimdsm
        $path = preg_replace('/^.*?lana_shop\//', 'lana_shop/', $path);
        return $path;
    }

    /**
     * Generate image URL with transformations
     *
     * @param string $publicId
     * @param array $transformations
     * @return string
     */
    public function getImageUrl(string $publicId, array $transformations = []): string
    {
        $defaultTransformations = config('cloudinary.transformations.medium', []);
        $transformations = array_merge($defaultTransformations, $transformations);

        return $this->cloudinary->image($publicId)->transformation($transformations)->toUrl();
    }

    /**
     * Get thumbnail URL
     *
     * @param string $publicId
     * @return string
     */
    public function getThumbnailUrl(string $publicId): string
    {
        $transformations = config('cloudinary.transformations.thumbnail', []);
        return $this->getImageUrl($publicId, $transformations);
    }

    /**
     * Get large image URL
     *
     * @param string $publicId
     * @return string
     */
    public function getLargeUrl(string $publicId): string
    {
        $transformations = config('cloudinary.transformations.large', []);
        return $this->getImageUrl($publicId, $transformations);
    }
} 