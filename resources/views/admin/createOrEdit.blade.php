@extends('admin::admin.layouts.master')

@section('title', 'Coupons Management')
@section('page-title', isset($coupon) ? 'Edit Coupon' : 'Create Coupon')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupon Manager</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ isset($coupon) ? 'Edit Coupon' : 'Create Coupon' }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Start Coupon Content -->
    <form action="{{ isset($coupon) ? route('admin.coupons.update', $coupon->id) : route('admin.coupons.store') }}"
        method="POST" enctype="multipart/form-data" id="couponForm">
        @csrf
        @if(isset($coupon))
        @method('PUT')
        @endif

        <div class="row">
            <div class="col-8">
                <!-- card section -->
                <div class="card bg-white">
                    <!--card header section -->
                    <div class="card-header bg-white border-bottom border-gray-200">
                        <h4 class="card-title">
                            Coupon Information
                        </h4>
                    </div>
                    <!--card body section -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Coupon Code <span class="text-danger">*</span></label>
                                    <input type="text" name="code" id="code" class="form-control"
                                        value="{{ old('code', $coupon->code ?? '') }}"
                                        placeholder="Enter coupon code">
                                    @error('code')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Discount Type <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-control select2">
                                        <option value="">Select type</option>
                                        <option value="fixed" {{ old('type', $coupon->type ?? '') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                        <option value="percentage" {{ old('type', $coupon->type ?? '') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    </select>
                                    @error('type')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Discount Value <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" id="amount" class="form-control"
                                        value="{{ old('amount', $coupon->amount ?? '') }}"
                                        placeholder="Enter discount value" min="0" step="0.01">
                                    @error('amount')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Minimum Cart Value</label>
                                    <input type="number" name="min_cart_value" id="min_cart_value" class="form-control"
                                        value="{{ old('min_cart_value', $coupon->min_cart_value ?? '') }}"
                                        placeholder="Enter minimum cart value" min="0" step="0.01">
                                    @error('min_cart_value')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Maximum Uses</label>
                                    <input type="number" name="max_uses" id="max_uses" class="form-control"
                                        value="{{ old('max_uses', $coupon->max_uses ?? '') }}"
                                        placeholder="Enter maximum uses" min="1">
                                    @error('max_uses')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control select2">
                                        <option value="1" {{ old('status', $coupon->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('status', $coupon->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <input type="text" name="start_date" id="start_date" class="form-control"
                                        value="{{ old('start_date', $coupon->start_date ?? '') }}" placeholder="Coupon start date">
                                    @error('start_date')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="text" name="end_date" id="end_date" class="form-control"
                                        value="{{ old('end_date', $coupon->end_date ?? '') }}" placeholder="Coupon end date">
                                    @error('end_date')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="3"
                                        placeholder="Internal notes...">{{ old('notes', $coupon->notes ?? '') }}</textarea>
                                    @error('notes')
                                    <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                {{ isset($coupon) ? 'Update' : 'Save' }}
                            </button>
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                                Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card bg-white">
                    <div class="card-header bg-white border-bottom border-gray-200">
                        <h4 class="card-title">Coupon Applicability</h4>
                    </div>
                    <div class="card-body">
                        @if(class_exists('admin\\products\\Models\\Product') && class_exists('admin\\categories\\Models\\Category'))
                        <div class="form-group">
                            <label for="categories">Categories</label>
                            <select name="categories[]" id="categories" multiple class="form-control select2">
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (isset($selectedCategories) && in_array($category->id, $selectedCategories)) || (isset($coupon) && $coupon->categories->contains($category->id)) ? 'selected' : '' }}>{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="products">Products</label>
                            <select name="products[]" id="products" multiple class="form-control select2">
                                @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ (isset($selectedProducts) && in_array($product->id, $selectedProducts)) || (isset($coupon) && $coupon->products->contains($product->id)) ? 'selected' : '' }}>{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        @if(class_exists('admin\\courses\\Models\\Course'))
                        <div class="form-group">
                            <label for="courses">Courses</label>
                            <select name="courses[]" id="courses" multiple class="form-control select2">
                                @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ (isset($selectedCourses) && in_array($course->id, $selectedCourses)) || (isset($coupon) && $coupon->courses->contains($course->id)) ? 'selected' : '' }}>{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->
    </form>
    <!-- End Coupon Content -->
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    $(document).ready(function() {
        ['#courses', '#products', '#categories'].forEach(function(selector) {
            $(selector).select2({
                placeholder: "Select " + selector.replace('#', ''),
                allowClear: true,
                width: '100%'
            });
        });

        try {
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2();
            }
        } catch (error) {
            console.log('Select2 error:', error);
        }

        // Datepicker for start_date and end_date
        function customizeDatepickerButtons(input, inst) {
            setTimeout(function() {
                var buttonPane = $(inst.dpDiv).find('.ui-datepicker-buttonpane');
                buttonPane.find('.ui-datepicker-close').hide();
                buttonPane.find('.ui-datepicker-current').hide();
                buttonPane.find('.ui-datepicker-clear').remove();
                $('<button type="button" class="ui-datepicker-clear ui-state-default ui-priority-primary ui-corner-all" style="margin-left:8px;">Clear</button>')
                    .appendTo(buttonPane)
                    .on('click', function() {
                        $(input).val('');
                        $(input).datepicker('hide');
                    });
            }, 1);
        }
        var today = new Date();
        var yyyy = today.getFullYear();
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var dd = String(today.getDate()).padStart(2, '0');
        var minDate = yyyy + '-' + mm + '-' + dd;
        $('#start_date, #end_date').datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            autoclose: true,
            minDate: minDate,
            beforeShow: customizeDatepickerButtons,
            onChangeMonthYear: function(year, month, inst) {
                var input = inst.input ? inst.input[0] : null;
                if (input) customizeDatepickerButtons(input, inst);
            },
            onSelect: function(dateText, inst) {
                var input = inst.input ? inst.input[0] : null;
                if (input) customizeDatepickerButtons(input, inst);
            }
        });
        $('#start_date, #end_date').on('focus click', function() {
            $(this).datepicker('show');
        });

        // Helper functions for error display
        function showError(inputId, message) {
            const input = $('#' + inputId);
            const errorDiv = input.next('.client-validation-error');
            if (errorDiv.length) {
                errorDiv.text(message);
            } else {
                input.after('<div class="text-danger client-validation-error mt-1" style="font-size: 12px;">' + message + '</div>');
            }
        }

        function clearError(inputId) {
            const input = $('#' + inputId);
            input.next('.client-validation-error').remove();
        }

        // Real-time validation for Coupon Code
        $('#code').on('blur keyup', function() {
            const value = $(this).val().trim();
            if (value === '') {
                showError('code', 'Coupon code is required.');
            } else if (value.length < 2) {
                showError('code', 'Coupon code must be at least 2 characters.');
            } else if (value.length > 50) {
                showError('code', 'Coupon code must not exceed 50 characters.');
            } else {
                clearError('code');
            }
        });

        // Real-time validation for Discount Type
        $('#type').on('blur change', function() {
            const value = $(this).val();
            if (value === '') {
                showError('type', 'Discount type is required.');
            } else {
                clearError('type');
            }
        });

        // Real-time validation for Discount Value
        $('#amount').on('blur keyup', function() {
            const value = $(this).val();
            if (value === '') {
                showError('amount', 'Discount value is required.');
            } else if (parseFloat(value) < 0) {
                showError('amount', 'Discount value must be at least 0.');
            } else {
                clearError('amount');
            }
        });

        // Real-time validation for Maximum Uses
        $('#max_uses').on('blur keyup', function() {
            const value = $(this).val();
            if (value !== '' && parseInt(value) < 1) {
                showError('max_uses', 'Maximum uses must be at least 1.');
            } else {
                clearError('max_uses');
            }
        });

        // Real-time validation for Status
        $('#status').on('blur change', function() {
            const value = $(this).val();
            if (value === '') {
                showError('status', 'Status is required.');
            } else {
                clearError('status');
            }
        });

        // Real-time validation for Notes
        $('#notes').on('blur keyup', function() {
            const value = $(this).val().trim();
            if (value.length > 500) {
                showError('notes', 'Notes must not exceed 500 characters.');
            } else {
                clearError('notes');
            }
        });

        // Form submission validation
        $('#couponForm').on('submit', function(e) {
            $('.client-validation-error').remove();
            var hasErrors = false;
            if ($('#code').val().trim() === '') {
                showError('code', 'Coupon code is required.');
                hasErrors = true;
            }
            if ($('#type').val() === '') {
                showError('type', 'Discount type is required.');
                hasErrors = true;
            }
            if ($('#amount').val() === '') {
                showError('amount', 'Discount value is required.');
                hasErrors = true;
            }
            if ($('#status').val() === '') {
                showError('status', 'Status is required.');
                hasErrors = true;
            }
            if ($('#start_date').val() !== '' && !/^\d{4}-\d{2}-\d{2}$/.test($('#start_date').val())) {
                showError('start_date', 'Start date format must be YYYY-MM-DD.');
                hasErrors = true;
            }
            if ($('#end_date').val() !== '' && !/^\d{4}-\d{2}-\d{2}$/.test($('#end_date').val())) {
                showError('end_date', 'End date format must be YYYY-MM-DD.');
                hasErrors = true;
            }
            if (hasErrors) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $('.client-validation-error').first().offset().top - 100
                }, 500);
                return false;
            }
            $('#saveBtn').prop('disabled', true)
                .html('<i class="mdi mdi-loading mdi-spin"></i> Saving...');
        });
    });
</script>
@endpush