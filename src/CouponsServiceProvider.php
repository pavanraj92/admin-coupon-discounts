<?php

namespace Admin\Coupons;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CouponsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load views from published module, resource path, and package fallback
        $this->loadViewsFrom([
            base_path('Modules/Coupons/resources/views'),
            resource_path('views/admin/coupon'),
            __DIR__ . '/../resources/views'
        ], 'coupons');

        $this->mergeConfigFrom(__DIR__ . '/../config/coupons.php', 'coupons.config');

        // Also register module views with a specific namespace for explicit usage
        if (is_dir(base_path('Modules/Coupons/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/Coupons/resources/views'), 'coupons-module');
        }
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        if (is_dir(base_path('Modules/Coupons/database/migrations'))) {
            $this->loadMigrationsFrom(base_path('Modules/Coupons/database/migrations'));
        }

        $this->mergeConfigFrom(__DIR__ . '/../config/coupons.php', 'coupons.config');
        if (file_exists(base_path('Modules/Coupons/config/coupons.php'))) {
            $this->mergeConfigFrom(base_path('Modules/Coupons/config/coupons.php'), 'coupons.config');
        }

        $this->publishes([
            __DIR__ . '/../config/' => base_path('Modules/Coupons/config/'),
            __DIR__ . '/../database/migrations' => base_path('Modules/Coupons/database/migrations'),
            __DIR__ . '/../resources/views/admin' => base_path('Modules/Coupons/resources/views/'),
        ], 'coupons');

        $this->registerAdminRoutes();
    }

    protected function registerAdminRoutes()
    {
        if (!Schema::hasTable('admins')) {
            return; // Avoid errors before migration
        }

        $slug = DB::table('admins')->latest()->value('website_slug') ?? 'admin';

        Route::middleware('web')
            ->prefix("{$slug}/admin") // dynamic prefix
            ->group(function () {
                // Load routes from published module first, then fallback to package
                if (file_exists(base_path('Modules/Coupons/routes/web.php'))) {
                    $this->loadRoutesFrom(base_path('Modules/Coupons/routes/web.php'));
                } else {
                    $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
                }
            });
    }

    public function register()
    {
        // Register the publish command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Admin\Coupons\Console\Commands\PublishCouponsModuleCommand::class,
                \Admin\Coupons\Console\Commands\CheckModuleStatusCommand::class,
                \Admin\Coupons\Console\Commands\DebugCouponsCommand::class,
                \Admin\Coupons\Console\Commands\TestViewResolutionCommand::class,
            ]);
        }
    }

    /**
     * Publish files with namespace transformation
     */
    protected function publishWithNamespaceTransformation()
    {
        // Define the files that need namespace transformation
        $filesWithNamespaces = [
            // Controllers
            __DIR__ . '/../src/Controllers/CouponManagerController.php' => base_path('Modules/Coupons/app/Http/Controllers/Admin/CouponManagerController.php'),

            // Models
            __DIR__ . '/../src/Models/Coupon.php' => base_path('Modules/Coupons/app/Models/Coupon.php'),

            // Requests
            __DIR__ . '/../src/Requests/StoreCouponRequest.php' => base_path('Modules/Coupons/app/Http/Requests/StoreCouponRequest.php'),
            __DIR__ . '/../src/Requests/UpdateCouponRequest.php' => base_path('Modules/Coupons/app/Http/Requests/UpdateCouponRequest.php'),

            // Routes
            __DIR__ . '/routes/web.php' => base_path('Modules/Coupons/routes/web.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                // Create destination directory if it doesn't exist
                File::ensureDirectoryExists(dirname($destination));

                // Read the source file
                $content = File::get($source);

                // Transform namespaces based on file type
                $content = $this->transformNamespaces($content, $source);

                // Write the transformed content to destination
                File::put($destination, $content);
            }
        }
    }

    /**
     * Transform namespaces in PHP files
     */
    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            'namespace Admin\\Coupons\\Controllers;' => 'namespace Modules\\Coupons\\app\\Http\\Controllers\\Admin;',
            'namespace Admin\\Coupons\\Models;' => 'namespace Modules\\Coupons\\app\\Models;',
            'namespace Admin\\Coupons\\Requests;' => 'namespace Modules\\Coupons\\app\\Http\\Requests;',
            'use Admin\\Coupons\\Controllers\\' => 'use Modules\\Coupons\\app\\Http\\Controllers\\Admin\\',
            'use Admin\\Coupons\\Models\\' => 'use Modules\\Coupons\\app\\Models\\',
            'use Admin\\Coupons\\Requests\\' => 'use Modules\\Coupons\\app\\Http\\Requests\\',
            'Admin\\Coupons\\Controllers\\CouponManagerController' => 'Modules\\Coupons\\app\\Http\\Controllers\\Admin\\CouponManagerController',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = $this->transformControllerNamespaces($content);
        } elseif (str_contains($sourceFile, 'Models')) {
            $content = $this->transformModelNamespaces($content);
        } elseif (str_contains($sourceFile, 'Requests')) {
            $content = $this->transformRequestNamespaces($content);
        } elseif (str_contains($sourceFile, 'routes')) {
            $content = $this->transformRouteNamespaces($content);
        }

        return $content;
    }

    /**
     * Transform controller-specific namespaces
     */
    protected function transformControllerNamespaces($content)
    {
        // Update use statements for models and requests
        $content = str_replace(
            'use Admin\\Coupons\\Models\\Coupon;',
            'use Modules\\Coupons\\app\\Models\\Coupon;',
            $content
        );
        $content = str_replace(
            'use Admin\\Coupons\\Requests\\StoreCouponRequest;',
            'use Modules\\Coupons\\app\\Http\\Requests\\StoreCouponRequest;',
            $content
        );
        $content = str_replace(
            'use Admin\\Coupons\\Requests\\UpdateCouponRequest;',
            'use Modules\\Coupons\\app\\Http\\Requests\\UpdateCouponRequest;',
            $content
        );
        return $content;
    }

    /**
     * Transform model-specific namespaces
     */
    protected function transformModelNamespaces($content)
    {
        // Any model-specific transformations
        return $content;
    }

    /**
     * Transform request-specific namespaces
     */
    protected function transformRequestNamespaces($content)
    {
        // Any request-specific transformations
        return $content;
    }

    /**
     * Transform route-specific namespaces
     */
    protected function transformRouteNamespaces($content)
    {
        // Update controller references in routes
        $content = str_replace(
            'Admin\\Coupons\\Controllers\\CouponManagerController',
            'Modules\\Coupons\\app\\Http\\Controllers\\Admin\\CouponManagerController',
            $content
        );
        return $content;
    }
}
