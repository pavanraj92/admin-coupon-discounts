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
            @include('coupons::admin.partials.filter')
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @admincan('product_coupons_manager_create')
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
                                    <th>S. No.</th>
                                    <th>@sortablelink('code', 'Code', [], ['class' => 'text-dark'])</th>
                                    <th>@sortablelink('type', 'Type', [], ['class' => 'text-dark'])</th>
                                    <th>@sortablelink('amount', 'Amount', [], ['class' => 'text-dark'])</th>
                                    <th>@sortablelink('min_cart_value', 'Min Cart Value', [], ['class' => 'text-dark'])</th>
                                    <th>@sortablelink('max_uses', 'Max Uses', [], ['class' => 'text-dark'])</th>
                                    <th>@sortablelink('status', 'Status', [], ['class' => 'text-dark'])</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($coupons as $coupon)
                                <tr>
                                    <td scope="row">{{ ($coupons->currentPage() - 1) * $coupons->perPage() + $loop->iteration }}</td>
                                    <td><strong>{{ $coupon?->code ?? 'N/A' }}</strong></td>
                                    <td>{{ config('coupons.types.' . $coupon?->type, ucfirst($coupon?->type)) ?? 'N/A' }}</td>
                                    <td>{{ $coupon?->amount ?? 'N/A' }}</td>
                                    <td>{{ $coupon?->min_cart_value ?? 'N/A' }}</td>
                                    <td>{{ $coupon?->max_uses ?? 'N/A' }}</td>
                                    <td>
                                        <a href="javascript:void(0)"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Click to change status to {{ $coupon->status ? 'inactive' : 'active' }}"
                                            data-url="{{ route('admin.coupons.updateStatus') }}"
                                            data-method="POST"
                                            data-status="{{ $coupon->status ? 0 : 1 }}"
                                            data-id="{{ $coupon->id }}"
                                            class="btn btn-sm update-status {{ $coupon->status ? 'btn-success' : 'btn-warning' }}">
                                            {{ $coupon?->status ? 'Active' : 'Inactive' }}
                                        </a>
                                    </td>
                                    <td>
                                        @admincan('product_coupons_manager_view')
                                        <a href="{{ route('admin.coupons.show', $coupon->id) }}" data-toggle="tooltip" title="View" class="btn btn-warning btn-sm mr-1"><i class="mdi mdi-eye"></i></a>
                                        @endadmincan
                                        @admincan('product_coupons_manager_edit')
                                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" data-toggle="tooltip" title="Edit" class="btn btn-success btn-sm mr-1"><i class="mdi mdi-pencil"></i></a>
                                        @endadmincan
                                        @admincan('product_coupons_manager_delete')
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
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No records found.</td>
                                </tr>
                                @endforelse
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