<?php

namespace Admin\Coupons\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoftDeletingPivot extends Pivot
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
}
