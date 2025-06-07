<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::active()->latest()->get();

        return response()->json([
            'success' => true,
            'testimonials' => $testimonials
        ]);
    }

    public function show(Testimonial $testimonial)
    {
        if (!$testimonial->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Testimonial not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'testimonial' => $testimonial
        ]);
    }
}
