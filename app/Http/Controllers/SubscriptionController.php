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
                        return [
                            'id' => $subscription->id,
                            'plan' => ucwords($subscription->plan->name),
                            'amount' => number_format($subscription->plan->price, 2),
                            'amount_paid' => number_format($subscription->amount_paid, 2),
                            'start_date' => $subscription->start_date->format('M d, Y'),
                            'end_date' => $subscription->end_date->format('M d, Y'),
                            'payment_method' => ucwords($subscription->payment_method),
                            'status' => view('portal.subscription.partials.subscription_status', ['subscription' => $subscription])->render(),
                            'action' => view('portal.subscription.partials.subscription_actions', ['subscription' => $subscription])->render(),
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
