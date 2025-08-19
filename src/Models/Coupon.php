<?php

namespace Admin\Coupons\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Kyslik\ColumnSortable\Sortable;

class Coupon extends Model
{
    use SoftDeletes, Sortable;

    public function categories()
    {
        if (class_exists(\admin\categories\Models\Category::class)) {
            return $this->belongsToMany(\admin\categories\Models\Category::class, 'coupon_category', 'coupon_id', 'category_id')
                ->using('App\Pivots\SoftDeletingPivot')
                ->withTimestamps()
                ->withPivot('deleted_at');
        }
    }

    public function products()
    {
        if (class_exists(\admin\products\Models\Product::class)) {
            return $this->belongsToMany(\admin\products\Models\Product::class, 'coupon_product', 'coupon_id', 'product_id')
                ->using('App\Pivots\SoftDeletingPivot')
                ->withTimestamps()
                ->withPivot('deleted_at');
        }
    }

    public function courses()
    {
        if (class_exists(\admin\courses\Models\Course::class)) {
            return $this->belongsToMany(\admin\courses\Models\Course::class, 'coupon_course', 'coupon_id', 'course_id')
                ->using('App\\Pivots\\SoftDeletingPivot')
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
}
