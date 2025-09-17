<?php

namespace Admin\Coupons\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Kyslik\ColumnSortable\Sortable;
use admin\categories\Models\Category;
use admin\products\Models\Product;
use admin\courses\Models\Course;
use Illuminate\Support\Facades\DB;

class Coupon extends Model
{
    use SoftDeletes, Sortable;

    public function categories()
    {
        if (self::isModuleInstalled('categories')) {
            return $this->belongsToMany(Category::class, 'coupon_category', 'coupon_id', 'category_id')
                ->using(\Admin\Coupons\Pivots\SoftDeletingPivot::class)
                ->withTimestamps()
                ->withPivot('deleted_at');
        }
    }

    public function products()
    {
        if (self::isModuleInstalled('products')) {
            return $this->belongsToMany(Product::class, 'coupon_product', 'coupon_id', 'product_id')
                ->using(\Admin\Coupons\Pivots\SoftDeletingPivot::class)
                ->withTimestamps()
                ->withPivot('deleted_at');
        }
    }

    public function courses()
    {
        if (self::isModuleInstalled('courses')) {
            return $this->belongsToMany(Course::class, 'coupon_course', 'coupon_id', 'course_id')
                ->using(\Admin\Coupons\Pivots\SoftDeletingPivot::class)
                ->withTimestamps()
                ->withPivot('deleted_at');
        }
    }

    protected $fillable = [
        'code',
        'type',
        'amount',
        'min_cart_value',
        'max_uses',
        'status',
        'start_date',
        'end_date',
        'notes'
    ];

    public $sortable = [
        'code',
        'type',
        'amount',
        'min_cart_value',
        'max_uses',
        'status',
        'start_date',
        'end_date',
        'created_at',
    ];

    // Example: Add a mutator for code
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = strtoupper($value);
    }

    /**
     * filter by code
     */
    public function scopeFilter($query, $code)
    {
        if ($code) {
            return $query->where('code', 'like', '%' . $code . '%');
        }
        return $query;
    }
    /**
     * filter by status
     */
    public function scopeFilterByStatus($query, $status)
    {
        if ($status !== null && $status !== '') {
            return $query->where('status', (int)$status);
        }
        return $query;
    }

    public static function getPerPageLimit(): int
    {
        return Config::has('get.admin_page_limit')
            ? Config::get('get.admin_page_limit')
            : 10;
    }

    public static function isModuleInstalled($moduleName)
    {
        return DB::table('packages')
            ->where('name', $moduleName)
            ->where('is_installed', 1)
            ->exists();
    }
}