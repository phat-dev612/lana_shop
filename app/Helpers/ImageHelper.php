<?php

namespace App\Helpers;

use App\Services\CloudinaryService;

class ImageHelper
{
    protected static $cloudinaryService;

    public static function getCloudinaryService()
    {
        if (!self::$cloudinaryService) {
            self::$cloudinaryService = app(CloudinaryService::class);
        }
        return self::$cloudinaryService;
    }

    /**
     * Get product image URL with specified size
     *
     * @param string|null $imageUrl
     * @param string $size
     * @return string
     */
    public static function getProductImage($imageUrl, $size = 'medium')
    {
        if (!$imageUrl) {
            return asset('images/placeholder-product.jpg');
        }

        // If it's already a Cloudinary URL, return as is
        if (str_contains($imageUrl, 'cloudinary.com')) {
            return $imageUrl;
        }

        // If it's a local image, return the asset URL
        if (!str_contains($imageUrl, 'http')) {
            return asset($imageUrl);
        }

        return $imageUrl;
    }

    /**
     * Get product thumbnail URL
     *
     * @param string|null $imageUrl
     * @return string
     */
    public static function getProductThumbnail($imageUrl)
    {
        return self::getProductImage($imageUrl, 'thumbnail');
    }

    /**
     * Get product large image URL
     *
     * @param string|null $imageUrl
     * @return string
     */
    public static function getProductLarge($imageUrl)
    {
        return self::getProductImage($imageUrl, 'large');
    }

    /**
     * Get user avatar URL
     *
     * @param string|null $avatarUrl
     * @return string
     */
    public static function getUserAvatar($avatarUrl)
    {
        if (!$avatarUrl) {
            return asset('images/default-avatar.png');
        }

        return $avatarUrl;
    }

    /**
     * Generate responsive image URLs for different screen sizes
     *
     * @param string|null $imageUrl
     * @return array
     */
    public static function getResponsiveImages($imageUrl)
    {
        return [
            'thumbnail' => self::getProductThumbnail($imageUrl),
            'medium' => self::getProductImage($imageUrl, 'medium'),
            'large' => self::getProductLarge($imageUrl),
        ];
    }

    /**
     * Check if image URL is from Cloudinary
     *
     * @param string|null $imageUrl
     * @return bool
     */
    public static function isCloudinaryImage($imageUrl)
    {
        return $imageUrl && str_contains($imageUrl, 'cloudinary.com');
    }

    /**
     * Get image dimensions from Cloudinary URL
     *
     * @param string $imageUrl
     * @return array|null
     */
    public static function getImageDimensions($imageUrl)
    {
        if (!self::isCloudinaryImage($imageUrl)) {
            return null;
        }

        // Extract width and height from Cloudinary URL if available
        if (preg_match('/w_(\d+),h_(\d+)/', $imageUrl, $matches)) {
            return [
                'width' => (int) $matches[1],
                'height' => (int) $matches[2],
            ];
        }

        return null;
    }
} 