<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure your Cloudinary settings. You can get these
    | values from your Cloudinary dashboard.
    |
    */

    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
    'api_key' => env('CLOUDINARY_API_KEY'),
    'api_secret' => env('CLOUDINARY_API_SECRET'),
    'secure' => env('CLOUDINARY_SECURE', true),

    /*
    |--------------------------------------------------------------------------
    | Default Upload Preset
    |--------------------------------------------------------------------------
    |
    | The default upload preset to use when uploading images.
    |
    */

    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET', 'ml_default'),

    /*
    |--------------------------------------------------------------------------
    | Default Folder
    |--------------------------------------------------------------------------
    |
    | The default folder to upload images to in Cloudinary.
    |
    */

    'folder' => env('CLOUDINARY_FOLDER', 'lana_shop'),

    /*
    |--------------------------------------------------------------------------
    | Image Transformations
    |--------------------------------------------------------------------------
    |
    | Default transformations to apply to uploaded images.
    |
    */

    'transformations' => [
        'thumbnail' => [
            'width' => 300,
            'height' => 300,
            'crop' => 'fill',
            'quality' => 'auto',
        ],
        'medium' => [
            'width' => 600,
            'height' => 600,
            'crop' => 'fill',
            'quality' => 'auto',
        ],
        'large' => [
            'width' => 1200,
            'height' => 1200,
            'crop' => 'fill',
            'quality' => 'auto',
        ],
    ],
]; 