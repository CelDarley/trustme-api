<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::where('is_active', true)->get();

        return response()->json([
            'success' => true,
            'plans' => $plans
        ]);
    }

    public function show(Plan $plan)
    {
        return response()->json([
            'success' => true,
            'plan' => $plan
        ]);
    }
}
