@extends('admin::admin.layouts.master')

@section('title', 'View Coupon - ' . $coupon->code)
@section('page-title', 'Coupon Details')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $coupon->code }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="card-title mb-0">{{ $coupon->code }} - {{ ucfirst($coupon->type) }}</h4>
                        <div>
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary ml-2">
                                <i class="mdi mdi-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-3">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">Coupon Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Coupon Code:</label>
                                                <p><strong>{{ $coupon->code }}</strong></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Type:</label>
                                                <p><span class="badge badge-info">{{ ucfirst($coupon->type) }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Discount Value:</label>
                                                <p>{{ $coupon->amount }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Minimum Cart Value:</label>
                                                <p>{{ $coupon->min_cart_value }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Maximum Uses:</label>
                                                <p>{{ $coupon->max_uses }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Status:</label>
                                                <p>
                                                    <span class="badge {{ $coupon->status == 1 ? 'badge-success' : 'badge-secondary' }}">
                                                        {{ $coupon->status == 1 ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Start Date:</label>
                                                <p>{{ $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('F d, Y') : 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">End Date:</label>
                                                <p>{{ $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('F d, Y') : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Categories:</label>
                                                @if($coupon->categories->count())
                                                <p>
                                                    @foreach($coupon->categories as $category)
                                                    <span class="badge badge-primary">{{ $category->title }}</span>
                                                    @endforeach
                                                </p>
                                                @else
                                                <p>N/A</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Products:</label>
                                                @if($coupon->products->count())
                                                <p>
                                                    @foreach($coupon->products as $product)
                                                    <span class="badge badge-success">{{ $product->name }}</span>
                                                    @endforeach
                                                </p>
                                                @else
                                                <p>N/A</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">Courses:</label>
                                                @if($coupon->courses->count())
                                                <p>
                                                    @foreach($coupon->courses as $course)
                                                    <span class="badge badge-info">{{ $course->title }}</span>
                                                    @endforeach
                                                </p>
                                                @else
                                                <p>N/A</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card mb-3">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">Coupon Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Created:</label>
                                        <p>{{ $coupon->created_at ? $coupon->created_at->format('M d, Y \a\t g:i A') : 'N/A' }}</p>
                                    </div>
                                    @if($coupon->notes)
                                    <div class="form-group">
                                        <label class="font-weight-bold">Notes:</label>
                                        <p>{{ $coupon->notes }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card h-100">
                                <div class="card-header bg-primary">
                                    <h5 class="mb-0 text-white font-bold">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-column">
                                        @admincan('product_coupons_manager_edit')
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-warning mb-2">
                                            <i class="mdi mdi-pencil"></i> Edit Coupon
                                        </a>
                                        @endadmincan
                                        @admincan('product_coupons_manager_delete')
                                        <button type="button" class="btn btn-danger delete-btn"
                                            data-url="{{ route('admin.coupons.destroy', $coupon) }}" data-text="Are you sure you want to delete this record?" data-method="DELETE">
                                            <i class="mdi mdi-delete"></i> Delete Coupon
                                        </button>
                                        @endadmincan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Delete functionality
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var currentElement = $(this);
            var id = $(this).data('id');
            var url = $(this).data('url');
            var method = $(this).data('method') || 'POST'; // Default to POST if method is not specified
            var text = $(this).data('text') || "You won't be able to revert this!"; // Default text if not specified

            Swal.fire({
                text: text,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
                customClass: {
                    confirmButton: "btn btn-outline-danger",
                    cancelButton: "btn btn-outline-success",
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: method,
                        url: url,
                        data: {
                            id: id
                        },
                        success: function(response) {
                            if (response.success) {
                                currentElement.closest("tr").remove();
                                toastr.success(response.message);
                                setTimeout(function() {
                                    window.location.href = "{{ route('admin.coupons.index') }}";
                                }, 1000);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error deleting record:", error);
                            console.error("Status:", status);
                            console.error("Response:", xhr.responseText);
                            toastr.error(
                                "An error occurred while deleting the record. please try again."
                            );
                        },
                    });
                }
            });
        });
    });
</script>
@endpush