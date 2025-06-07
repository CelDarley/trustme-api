<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SealController extends Controller
{
    public function index(Request $request)
    {
        $seals = $request->user()->seals()->latest()->get();

        return response()->json([
            'success' => true,
            'seals' => $seals
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user()->canCreateSeals()) {
            return response()->json([
                'success' => false,
                'message' => 'Seal limit reached for your current plan'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'document_type' => 'required|string|in:CPF,RG,CNH,Passport',
            'document_number' => 'required|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if document already exists for this user
        $existingSeal = Seal::where('user_id', $request->user()->id)
            ->where('document_type', $request->document_type)
            ->where('document_number', $request->document_number)
            ->first();

        if ($existingSeal) {
            return response()->json([
                'success' => false,
                'message' => 'This document is already registered'
            ], 422);
        }

        $seal = Seal::create([
            'user_id' => $request->user()->id,
            'document_type' => $request->document_type,
            'document_number' => $request->document_number,
            'verification_status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Seal created successfully. Verification is pending.',
            'seal' => $seal
        ], 201);
    }

    public function show(Request $request, Seal $seal)
    {
        if ($seal->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'seal' => $seal
        ]);
    }

    public function destroy(Request $request, Seal $seal)
    {
        if ($seal->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($seal->verification_status === 'verified') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete verified seal'
            ], 403);
        }

        $seal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Seal deleted successfully'
        ]);
    }
}
