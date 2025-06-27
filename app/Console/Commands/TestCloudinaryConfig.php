<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCloudinaryConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudinary:test-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Cloudinary configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Cloudinary configuration...');

        // Check configuration
        $cloudName = config('cloudinary.cloud_name');
        $apiKey = config('cloudinary.api_key');
        $apiSecret = config('cloudinary.api_secret');
        $secure = config('cloudinary.secure');
        $uploadPreset = config('cloudinary.upload_preset');
        $folder = config('cloudinary.folder');

        $this->table(
            ['Setting', 'Value', 'Status'],
            [
                ['Cloud Name', $cloudName ?: 'Not set', $cloudName ? '✓' : '✗'],
                ['API Key', $apiKey ?: 'Not set', $apiKey ? '✓' : '✗'],
                ['API Secret', $apiSecret ? '***' . substr($apiSecret, -4) : 'Not set', $apiSecret ? '✓' : '✗'],
                ['Secure', $secure ? 'true' : 'false', '✓'],
                ['Upload Preset', $uploadPreset ?: 'Not set', $uploadPreset ? '✓' : '✗'],
                ['Folder', $folder ?: 'Not set', $folder ? '✓' : '✗'],
            ]
        );

        if (!$cloudName || !$apiKey || !$apiSecret) {
            $this->error('❌ Cloudinary configuration is incomplete!');
            $this->line('');
            $this->line('Please add the following to your .env file:');
            $this->line('CLOUDINARY_CLOUD_NAME=your_cloud_name');
            $this->line('CLOUDINARY_API_KEY=your_api_key');
            $this->line('CLOUDINARY_API_SECRET=your_api_secret');
            return 1;
        }

        $this->info('✅ Cloudinary configuration looks good!');
        
        // Test Cloudinary SDK initialization
        try {
            $cloudinary = new \Cloudinary\Cloudinary([
                'cloud' => [
                    'cloud_name' => $cloudName,
                    'api_key' => $apiKey,
                    'api_secret' => $apiSecret,
                ],
            ]);
            
            $this->info('✅ Cloudinary SDK initialized successfully!');
        } catch (\Exception $e) {
            $this->error('❌ Failed to initialize Cloudinary SDK: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
} 