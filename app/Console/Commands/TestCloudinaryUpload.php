<?php

namespace App\Console\Commands;

use App\Services\CloudinaryService;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;

class TestCloudinaryUpload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudinary:test-upload {--file=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Cloudinary upload functionality';

    /**
     * Execute the console command.
     */
    public function handle(CloudinaryService $cloudinaryService)
    {
        $this->info('Testing Cloudinary upload...');

        // Check configuration
        $cloudName = config('cloudinary.cloud_name');
        $apiKey = config('cloudinary.api_key');
        $apiSecret = config('cloudinary.api_secret');

        if (!$cloudName || !$apiKey || !$apiSecret) {
            $this->error('Cloudinary configuration is missing. Please check your .env file.');
            return 1;
        }

        $this->info('✓ Cloudinary configuration found');

        // Test upload with a sample image
        $filePath = $this->option('file');
        
        if (!$filePath) {
            $this->error('Please provide a file path using --file option');
            return 1;
        }

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Uploading file: {$filePath}");

        try {
            // Create UploadedFile instance
            $uploadedFile = new UploadedFile(
                $filePath,
                basename($filePath),
                mime_content_type($filePath),
                null,
                true
            );

            $result = $cloudinaryService->uploadImage($uploadedFile, 'lana_shop/test');

            if ($result) {
                $this->info('✓ Upload successful!');
                $this->table(
                    ['Property', 'Value'],
                    [
                        ['Public ID', $result['public_id']],
                        ['URL', $result['url']],
                        ['Width', $result['width']],
                        ['Height', $result['height']],
                        ['Format', $result['format']],
                        ['Size (bytes)', $result['bytes']],
                    ]
                );
            } else {
                $this->error('✗ Upload failed');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('✗ Upload failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 