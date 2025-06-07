<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function createPreference(Request $request)
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

        $billingCycleText = match($request->billing_cycle) {
            'monthly' => 'Mensal',
            'six_months' => '6 Meses',
            'yearly' => 'Anual'
        };

        // This is a simplified version - in production you would integrate with MercadoPago SDK
        $preference = [
            'items' => [
                [
                    'title' => "Plano {$plan->name} - {$billingCycleText}",
                    'quantity' => 1,
                    'unit_price' => (float) $amount,
                    'currency_id' => 'BRL'
                ]
            ],
            'payer' => [
                'name' => $request->user()->name,
                'email' => $request->user()->email
            ],
            'back_urls' => [
                'success' => config('app.url') . '/payment/success',
                'failure' => config('app.url') . '/payment/failure',
                'pending' => config('app.url') . '/payment/pending'
            ],
            'auto_return' => 'approved',
            'external_reference' => $request->user()->id . '_' . $plan->id . '_' . $request->billing_cycle
        ];

        return response()->json([
            'success' => true,
            'preference' => $preference,
            'amount' => $amount,
            'plan' => $plan,
            'billing_cycle' => $request->billing_cycle
        ]);
    }

    public function processPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|string',
            'status' => 'required|string',
            'external_reference' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Parse external reference
        $parts = explode('_', $request->external_reference);
        if (count($parts) !== 3) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid external reference'
            ], 400);
        }

        [$userId, $planId, $billingCycle] = $parts;

        if ($request->status === 'approved') {
            $plan = Plan::findOrFail($planId);
            
            $amount = match($billingCycle) {
                'monthly' => $plan->monthly_price,
                'six_months' => $plan->six_months_price,
                'yearly' => $plan->yearly_price
            };

            $startsAt = now();
            $endsAt = match($billingCycle) {
                'monthly' => $startsAt->copy()->addMonth(),
                'six_months' => $startsAt->copy()->addMonths(6),
                'yearly' => $startsAt->copy()->addYear()
            };

            $subscription = Subscription::create([
                'user_id' => $userId,
                'plan_id' => $planId,
                'billing_cycle' => $billingCycle,
                'amount' => $amount,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'status' => 'active',
                'mercadopago_subscription_id' => $request->payment_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'subscription' => $subscription->load('plan')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Payment not approved',
            'status' => $request->status
        ], 400);
    }
}
