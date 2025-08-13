@extends('admin::admin.layouts.master')

@section('title', 'Coupons Management')
@section('page-title', 'Coupon Manager')
@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Coupon Manager</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <h4 class="card-title">Filter</h4>
                <form action="{{ route('admin.coupons.index') }}" method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="keyword">Search</label>
                                <input type="text" name="keyword" id="keyword" class="form-control" value="{{ app('request')->query('keyword') }}" placeholder="Coupon code">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control select2">
                                    <option value="">All</option>
                                    <option value="1" {{ app('request')->query('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ app('request')->query('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto mt-1 text-right">
                            <div class="form-group">
                                <button type="submit" form="filterForm" class="btn btn-primary mt-4">Filter</button>
                                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary mt-4">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @admincan('coupons_manager_create')
                    <div class="text-right">
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary mb-3">
                            Create New Coupon
                        </a>
                    </div>
                    @endadmincan
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">S. No.</th>
                                    <th scope="col">@sortablelink('code', 'Code', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                    <th scope="col">@sortablelink('type', 'Type', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                    <th scope="col">@sortablelink('amount', 'Amount', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                    <th scope="col">@sortablelink('min_cart_value', 'Min Cart Value', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                    <th scope="col">@sortablelink('max_uses', 'Max Uses', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                    <th scope="col">@sortablelink('status', 'Status', [], ['style' => 'color: #4F5467; text-decoration: none;'])</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($coupons) && $coupons->count() > 0)
                                @php
                                $i = ($coupons->currentPage() - 1) * $coupons->perPage() + 1;
                                @endphp
                                @foreach ($coupons as $coupon)
                                <tr>
                                    <th scope="row">{{ $i }}</th>
                                    <td><strong>{{ $coupon->code }}</strong></td>
                                    <td>{{ config('coupons.types.' . $coupon->type, ucfirst($coupon->type)) }}</td>
                                    <td>{{ $coupon->amount }}</td>
                                    <td>{{ $coupon->min_cart_value }}</td>
                                    <td>{{ $coupon->max_uses }}</td>
                                    <td>
                                        <!-- create update status functionality-->
                                        @if ($coupon->status == 1)
                                        <a href="javascript:void(0)" data-toggle="tooltip"
                                            data-placement="top" title="Click to change status to inactive"
                                            data-url="{{ route('admin.coupons.updateStatus') }}"
                                            data-method="POST" data-status="0"
                                            data-id="{{ $coupon->id }}"
                                            class="btn btn-success btn-sm update-status">Active</a>
                                        @else
                                        <a href="javascript:void(0)" data-toggle="tooltip"
                                            data-placement="top" title="Click to change status to active"
                                            data-url="{{ route('admin.coupons.updateStatus') }}"
                                            data-method="POST" data-status="1"
                                            data-id="{{ $coupon->id }}"
                                            class="btn btn-warning btn-sm update-status">Inactive</a>
                                        @endif
                                    </td>
                                    <td>
                                        @admincan('coupons_manager_view')
                                        <a href="{{ route('admin.coupons.show', $coupon->id) }}" data-toggle="tooltip" title="View" class="btn btn-warning btn-sm mr-1"><i class="mdi mdi-eye"></i></a>
                                        @endadmincan
                                        @admincan('coupons_manager_edit')
                                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" data-toggle="tooltip" title="Edit" class="btn btn-success btn-sm mr-1"><i class="mdi mdi-pencil"></i></a>
                                        @endadmincan
                                        @admincan('coupons_manager_delete')
                                        <a href="javascript:void(0)" data-toggle="tooltip"
                                            data-placement="top" title="Delete this record"
                                            data-url="{{ route('admin.coupons.destroy', $coupon->id) }}"
                                            data-text="Are you sure you want to delete this coupon?"
                                            data-method="DELETE"
                                            class="btn btn-danger btn-sm delete-record"><i
                                                class="mdi mdi-delete"></i></a>
                                        @endadmincan
                                    </td>
                                </tr>
                                @php $i++; @endphp
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8" class="text-center">No coupons found.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        @if (isset($coupons) && $coupons->count() > 0)
                        {{ $coupons->links('admin::pagination.custom-admin-pagination') }}
                        @endif
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
        try {
            if (typeof $.fn.tooltip !== 'undefined') {
                $('[data-toggle="tooltip"]').tooltip();
            }
        } catch (error) {
            console.log('Tooltip error:', error);
        }
        try {
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2();
            }
        } catch (error) {
            console.log('Select2 error:', error);
        }
    });
</script>
@endpush