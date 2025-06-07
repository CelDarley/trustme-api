<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $subscriptions = $request->user()->subscriptions()->with('plan')->get();

        return response()->json([
            'success' => true,
            'subscriptions' => $subscriptions
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,six_months,yearly'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $plan = Plan::findOrFail($request->plan_id);
        
        // Calculate amount based on billing cycle
        $amount = match($request->billing_cycle) {
            'monthly' => $plan->monthly_price,
            'six_months' => $plan->six_months_price,
            'yearly' => $plan->yearly_price
        };

        // Calculate end date
        $startsAt = now();
        $endsAt = match($request->billing_cycle) {
            'monthly' => $startsAt->copy()->addMonth(),
            'six_months' => $startsAt->copy()->addMonths(6),
            'yearly' => $startsAt->copy()->addYear()
        };

        $subscription = Subscription::create([
            'user_id' => $request->user()->id,
            'plan_id' => $plan->id,
            'billing_cycle' => $request->billing_cycle,
            'amount' => $amount,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Subscription created successfully',
            'subscription' => $subscription->load('plan')
        ], 201);
    }

    public function show(Request $request, Subscription $subscription)
    {
        if ($subscription->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'subscription' => $subscription->load('plan')
        ]);
    }

    public function cancel(Request $request, Subscription $subscription)
    {
        if ($subscription->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $subscription->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Subscription cancelled successfully'
        ]);
    }
}
