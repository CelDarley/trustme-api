<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::active()->ordered()->get();

        return response()->json([
            'success' => true,
            'faqs' => $faqs
        ]);
    }

    public function show(Faq $faq)
    {
        if (!$faq->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'faq' => $faq
        ]);
    }
}
