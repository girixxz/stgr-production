<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // OPTIMIZATION: Prevent lazy loading in production
        // Akan throw error jika ada N+1 query problem
        Model::preventLazyLoading(!app()->isProduction());

        // OPTIMIZATION: Strict mode untuk development, relaxed untuk production
        Model::shouldBeStrict(!app()->isProduction());

        // OPTIMIZATION: Query logging only in development
        if (config('app.debug') && config('app.env') === 'local') {
            DB::listen(function ($query) {
                // Log slow queries (more than 1 second)
                if ($query->time > 1000) {
                    logger()->warning('Slow Query Detected', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time . 'ms'
                    ]);
                }
            });
        }

        // OPTIMIZATION: Cache static data yang sering digunakan
        // Contoh: Product Categories, Material Categories, dll
        $this->cacheStaticData();
    }

    /**
     * Cache static data untuk mengurangi database query
     */
    protected function cacheStaticData(): void
    {
        // Cache untuk 1 hari (86400 detik)
        // Data ini jarang berubah, jadi aman di-cache
        View::composer('*', function ($view) {
            // Hanya cache jika bukan di local development
            if (app()->environment('production')) {
                $cacheTime = 86400; // 24 jam

                // Share ke semua view jika diperlukan
                // Uncomment jika diperlukan di navbar/sidebar
                /*
                $view->with([
                    'cachedProductCategories' => cache()->remember(
                        'product_categories', 
                        $cacheTime, 
                        fn() => \App\Models\ProductCategory::orderBy('product_name')->get()
                    ),
                    'cachedMaterialCategories' => cache()->remember(
                        'material_categories', 
                        $cacheTime, 
                        fn() => \App\Models\MaterialCategory::orderBy('material_name')->get()
                    ),
                ]);
                */
            }
        });
    }
}
