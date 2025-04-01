<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $subscriptions = Subscription::with('plan')
                    ->get()
                    ->map(function ($subscription) {
                        // Determine status badge and buttons based on payment_status
                        $statusBadge = '';
                        $actionButtons = '';

                        switch ($subscription->payment_status) {
                            case 'paid':
                                $statusBadge = '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Paid</span>';
                                $actionButtons = '
                                    <button class="btn btn-sm btn-outline-primary" disabled>
                                        <i class="fas fa-file-invoice me-1"></i> View Receipt
                                    </button>
                                ';
                                break;

                            case 'pending':
                                $statusBadge = '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Pending</span>';
                                $actionButtons = '
                                    <a href="' . route('subscription.pay', $subscription->id) . '" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-credit-card me-1"></i> Pay Now
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-times-circle me-1"></i> Cancel
                                    </button>
                                ';
                                break;

                            case 'failed':
                                $statusBadge = '<span class="badge bg-danger"><i class="fas fa-exclamation-circle me-1"></i> Failed</span>';
                                $actionButtons = '
                                    <button class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-sync-alt me-1"></i> Retry Payment
                                    </button>
                                    <a href="' . route('support.create') . '" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-headset me-1"></i> Support
                                    </a>
                                ';
                                break;

                            case 'partial':
                                $statusBadge = '<span class="badge bg-info"><i class="fas fa-money-bill-wave me-1"></i> Partial</span>';
                                $actionButtons = '
                                    <a href="' . route('subscription.pay', $subscription->id) . '" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="fas fa-wallet me-1"></i> Pay Balance
                                    </a>
                                    <button class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-receipt me-1"></i> Invoice
                                    </button>
                                ';
                                break;

                            default:
                                $statusBadge = '<span class="badge bg-secondary"><i class="fas fa-question-circle me-1"></i> Unknown</span>';
                                $actionButtons = '
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-search me-1"></i> Review
                                    </button>
                                ';
                        }

                        return [
                            'id' => $subscription->id,
                            'plan' => ucwords($subscription->plan->name),
                            'amount' => number_format($subscription->plan->price, 2),
                            'amount_paid' => number_format($subscription->amount_paid, 2),
                            'start_date' => $subscription->start_date->format('M d, Y'),
                            'end_date' => $subscription->end_date->format('M d, Y'),
                            'payment_method' => ucwords($subscription->payment_method),
                            'status' => $statusBadge,
                            'action' => $actionButtons,
                        ];
                    });

                return DataTables::of($subscriptions)
                    ->rawColumns(['status', 'action'])
                    ->make(true);
            } catch (\Exception $e) {
                Log::error('Failed to fetch subscriptions for DataTable', ['error' => $e->getMessage()]);
                return response()->json([
                    'error' => 'Failed to fetch subscription data. Please try again later.',
                ], 500);
            }
        }

        return view('portal.subscription.index');
    }

    public function pay(Request $request, string $id)
    {
        $subscription = Subscription::with('plan')->first();

        return view('portal.subscription.pay', compact('subscription'));
    }
}
