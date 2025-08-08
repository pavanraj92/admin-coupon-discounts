<?php

namespace admin\coupons\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishCouponsModuleCommand extends Command
{
    protected $signature = 'coupons:publish {--force : Force overwrite existing files}';
    protected $description = 'Publish Coupons module files with proper namespace transformation';

    public function handle()
    {
        $this->info('Publishing Coupons module files...');

        // Check if module directory exists
        $moduleDir = base_path('Modules/Coupons');
        if (!File::exists($moduleDir)) {
            File::makeDirectory($moduleDir, 0755, true);
        }

        // Publish with namespace transformation
        $this->publishWithNamespaceTransformation();
        
        // Publish other files
        $this->call('vendor:publish', [
            '--tag' => 'coupons',
            '--force' => $this->option('force')
        ]);

        // Update composer autoload
        $this->updateComposerAutoload();

        $this->info('Coupons module published successfully!');
        $this->info('Please run: composer dump-autoload');
    }

    protected function publishWithNamespaceTransformation()
    {
        $basePath = dirname(dirname(__DIR__)); // Go up to packages/admin/coupons/src

        $filesWithNamespaces = [
            // Controllers
            $basePath . '/Controllers/CouponManagerController.php' => base_path('Modules/Coupons/app/Http/Controllers/Admin/CouponManagerController.php'),

            // Models
            $basePath . '/Models/Coupon.php' => base_path('Modules/Coupons/app/Models/Coupon.php'),

            // Requests
            $basePath . '/Requests/CouponCreateRequest.php' => base_path('Modules/Coupons/app/Http/Requests/CouponCreateRequest.php'),
            $basePath . '/Requests/CouponUpdateRequest.php' => base_path('Modules/Coupons/app/Http/Requests/CouponUpdateRequest.php'),

            // Routes
            $basePath . '/routes/web.php' => base_path('Modules/Coupons/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname($destination));
                
                $content = File::get($source);
                $content = $this->transformNamespaces($content, $source);
                
                File::put($destination, $content);
                $this->info("Published: " . basename($destination));
            } else {
                $this->warn("Source file not found: " . $source);
            }
        }
    }

    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\coupons\\Controllers;' => 'namespace Modules\\Coupons\\app\\Http\\Controllers\\Admin;',
            'namespace admin\\coupons\\Models;' => 'namespace Modules\\Coupons\\app\\Models;',
            'namespace admin\\coupons\\Requests;' => 'namespace Modules\\Coupons\\app\\Http\\Requests;',

            // Use statements transformations
            'use admin\\coupons\\Controllers\\' => 'use Modules\\Coupons\\app\\Http\\Controllers\\Admin\\',
            'use admin\\coupons\\Models\\' => 'use Modules\\Coupons\\app\\Models\\',
            'use admin\\coupons\\Requests\\' => 'use Modules\\Coupons\\app\\Http\\Requests\\',

            // Class references in routes
            'admin\\coupons\\Controllers\\CouponManagerController' => 'Modules\\Coupons\\app\\Http\\Controllers\\Admin\\CouponManagerController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = str_replace('use admin\\coupons\\Models\\Coupon;', 'use Modules\\Coupons\\app\\Models\\Coupon;', $content);
            $content = str_replace('use admin\\coupons\\Requests\\CouponCreateRequest;', 'use Modules\\Coupons\\app\\Http\\Requests\\CouponCreateRequest;', $content);
            $content = str_replace('use admin\\coupons\\Requests\\CouponUpdateRequest;', 'use Modules\\Coupons\\app\\Http\\Requests\\CouponUpdateRequest;', $content);
        }

        return $content;
    }

    protected function updateComposerAutoload()
    {
        $composerFile = base_path('composer.json');
        $composer = json_decode(File::get($composerFile), true);

        // Add module namespace to autoload
        if (!isset($composer['autoload']['psr-4']['Modules\\Coupons\\'])) {
            $composer['autoload']['psr-4']['Modules\\Coupons\\'] = 'Modules/Coupons/app/';

            File::put($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('Updated composer.json autoload');
        }
    }
}
