<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $contracts = $request->user()->contracts()->latest()->get();

        return response()->json([
            'success' => true,
            'contracts' => $contracts
        ]);
    }

    public function store(Request $request)
    {
        if (!$request->user()->canCreateContracts()) {
            return response()->json([
                'success' => false,
                'message' => 'Contract limit reached for your current plan'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'party_a_name' => 'required|string|max:255',
            'party_a_email' => 'required|email|max:255',
            'party_b_name' => 'required|string|max:255',
            'party_b_email' => 'required|email|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $contract = Contract::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'content' => $request->content,
            'party_a_name' => $request->party_a_name,
            'party_a_email' => $request->party_a_email,
            'party_b_name' => $request->party_b_name,
            'party_b_email' => $request->party_b_email,
            'status' => 'draft'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contract created successfully',
            'contract' => $contract
        ], 201);
    }

    public function show(Request $request, Contract $contract)
    {
        if ($contract->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'contract' => $contract
        ]);
    }

    public function update(Request $request, Contract $contract)
    {
        if ($contract->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($contract->status === 'signed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update signed contract'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'party_a_name' => 'sometimes|required|string|max:255',
            'party_a_email' => 'sometimes|required|email|max:255',
            'party_b_name' => 'sometimes|required|string|max:255',
            'party_b_email' => 'sometimes|required|email|max:255',
            'status' => 'sometimes|in:draft,pending,signed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $contract->update($request->validated());

        if ($request->status === 'signed') {
            $contract->update(['signed_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Contract updated successfully',
            'contract' => $contract
        ]);
    }

    public function destroy(Request $request, Contract $contract)
    {
        if ($contract->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($contract->status === 'signed') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete signed contract'
            ], 403);
        }

        $contract->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contract deleted successfully'
        ]);
    }
}
