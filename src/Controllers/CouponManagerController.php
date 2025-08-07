<?php

namespace Admin\Coupons\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Admin\Coupons\Models\Coupon;
use Admin\Coupons\Requests\StoreCouponRequest;
use Admin\Coupons\Requests\UpdateCouponRequest;

class CouponManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('admincan_permission:product_coupons_manager_list')->only(['index']);
        $this->middleware('admincan_permission:product_coupons_manager_create')->only(['create', 'store']);
        $this->middleware('admincan_permission:product_coupons_manager_edit')->only(['edit', 'update']);
        $this->middleware('admincan_permission:product_coupons_manager_view')->only(['show']);
        $this->middleware('admincan_permission:product_coupons_manager_delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        try {
            $coupons = Coupon::query()
                ->filter($request->query('keyword'))
                ->filterByStatus($request->query('status'))
                ->sortable()
                ->latest()
                ->paginate(Coupon::getPerPageLimit())
                ->withQueryString();

            return view('coupons::index', compact('coupons'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load coupons: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $types = config('coupons.types', []);
            return view('coupons::createOrEdit', compact('types'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load coupon creation form: ' . $e->getMessage());
        }
    }

    public function store(StoreCouponRequest $request)
    {
        try {
            $coupon = Coupon::create($request->validated());
            return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create coupon: ' . $e->getMessage());
        }
    }

    public function show(Coupon $coupon)
    {
        try {
            return view('coupons::show', compact('coupon'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load coupon details: ' . $e->getMessage());
        }
    }

    public function edit(Coupon $coupon)
    {
        try {
            $types = config('coupons.types', []);
            return view('coupons::createOrEdit', compact('coupon', 'types'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load coupon for editing: ' . $e->getMessage());
        }
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        try {
            $data = $request->validated();
            $coupon->update($data);
            return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update coupon: ' . $e->getMessage());
        }
    }

    public function destroy(Coupon $coupon)
    {
        try {
            $coupon->delete();
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to delete coupon.', 'error' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Failed to delete coupon: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $coupon = Coupon::findOrFail($request->id);

            // Ensure status is cast to integer (0 or 1)
            $coupon->status = (int) $request->status;
            $coupon->save();

            // Create status html dynamically
            $dataStatus = $coupon->status === 1 ? 0 : 1;
            $label = $coupon->status === 1 ? 'Active' : 'Inactive';
            $btnClass = $coupon->status === 1 ? 'btn-success' : 'btn-warning';
            $tooltip = $coupon->status === 1 ? 'Click to change status to inactive' : 'Click to change status to active';

            $strHtml = '<a href="javascript:void(0)"'
                . ' data-toggle="tooltip"'
                . ' data-placement="top"'
                . ' title="' . $tooltip . '"'
                . ' data-url="' . route('admin.coupons.updateStatus') . '"'
                . ' data-method="POST"'
                . ' data-status="' . $dataStatus . '"'
                . ' data-id="' . $coupon->id . '"'
                . ' class="btn ' . $btnClass . ' btn-sm update-status">' . $label . '</a>';

            return response()->json(['success' => true, 'message' => 'Status updated to ' . $label, 'strHtml' => $strHtml]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
