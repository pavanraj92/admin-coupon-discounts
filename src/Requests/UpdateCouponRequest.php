<?php

namespace Admin\Coupons\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCouponRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $couponId = $this->route('coupon') ? $this->route('coupon')->id : null;
        return [
            'code' => 'required|string|max:50|unique:coupons,code,' . $couponId,
            'type' => 'required|in:fixed,percentage,free_shipping',
            'amount' => 'required|numeric|min:0',
            'min_cart_value' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'status' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:500',
        ];
    }
}
