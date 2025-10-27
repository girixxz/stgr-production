<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Cache;
use App\Models\ProductCategory;
use App\Models\MaterialCategory;
use App\Models\MaterialTexture;
use App\Models\MaterialSleeve;
use App\Models\MaterialSize;
use App\Models\Service;
use App\Models\Shipping;
use App\Models\Sale;
use App\Models\Province;

/**
 * Cache Helper untuk data static yang jarang berubah
 * 
 * Gunakan helper ini untuk mengurangi query database
 * Cache duration: 24 jam (86400 detik)
 */
class CacheHelper
{
    /**
     * Cache duration in seconds (24 hours)
     */
    const CACHE_DURATION = 86400;

    /**
     * Get cached product categories
     */
    public static function productCategories()
    {
        return Cache::remember('product_categories', self::CACHE_DURATION, function () {
            return ProductCategory::orderBy('product_name')->get();
        });
    }

    /**
     * Get cached material categories
     */
    public static function materialCategories()
    {
        return Cache::remember('material_categories', self::CACHE_DURATION, function () {
            return MaterialCategory::orderBy('material_name')->get();
        });
    }

    /**
     * Get cached material textures
     */
    public static function materialTextures()
    {
        return Cache::remember('material_textures', self::CACHE_DURATION, function () {
            return MaterialTexture::orderBy('texture_name')->get();
        });
    }

    /**
     * Get cached material sleeves
     */
    public static function materialSleeves()
    {
        return Cache::remember('material_sleeves', self::CACHE_DURATION, function () {
            return MaterialSleeve::orderBy('sleeve_name')->get();
        });
    }

    /**
     * Get cached material sizes
     */
    public static function materialSizes()
    {
        return Cache::remember('material_sizes', self::CACHE_DURATION, function () {
            return MaterialSize::orderBy('size_name')->get();
        });
    }

    /**
     * Get cached services
     */
    public static function services()
    {
        return Cache::remember('services', self::CACHE_DURATION, function () {
            return Service::orderBy('service_name')->get();
        });
    }

    /**
     * Get cached shippings
     */
    public static function shippings()
    {
        return Cache::remember('shippings', self::CACHE_DURATION, function () {
            return Shipping::orderBy('shipping_name')->get();
        });
    }

    /**
     * Get cached sales
     */
    public static function sales()
    {
        return Cache::remember('sales', self::CACHE_DURATION, function () {
            return Sale::orderBy('sales_name')->get();
        });
    }

    /**
     * Get cached provinces
     */
    public static function provinces()
    {
        return Cache::remember('provinces', self::CACHE_DURATION, function () {
            return Province::orderBy('province_name')->get();
        });
    }

    /**
     * Clear all static data cache
     * Call this method when master data is updated
     */
    public static function clearAll()
    {
        Cache::forget('product_categories');
        Cache::forget('material_categories');
        Cache::forget('material_textures');
        Cache::forget('material_sleeves');
        Cache::forget('material_sizes');
        Cache::forget('services');
        Cache::forget('shippings');
        Cache::forget('sales');
        Cache::forget('provinces');
    }

    /**
     * Clear specific cache
     */
    public static function clear($key)
    {
        Cache::forget($key);
    }

    /**
     * Warm up cache (preload semua data)
     * Gunakan setelah deployment atau update master data
     */
    public static function warmUp()
    {
        self::productCategories();
        self::materialCategories();
        self::materialTextures();
        self::materialSleeves();
        self::materialSizes();
        self::services();
        self::shippings();
        self::sales();
        self::provinces();
    }
}
