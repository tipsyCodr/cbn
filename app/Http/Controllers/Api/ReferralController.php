<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use Illuminate\Http\Request;
class ReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Get referrals for the authenticated user
            $referrals = Referral::where('from', $request->user()->id)->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $referrals
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch referrals',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Merge the authenticated user ID with the request data
            $data = $request->all();
            $data['from'] = $request->user()->id;
            
            $referral = Referral::create($data);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Referral created successfully',
                'data' => $referral
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create referral',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {
            // Find the record that belongs to the authenticated user
            $referral = Referral::where('id', $id)
                ->where('from', $request->user()->id)
                ->first();
            
            if (!$referral) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Referral not found'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $referral
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch referral',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            // Get the id from the request
            $id = $request->input('id');
            
            if (!$id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ID is required'
                ], 400);
            }
            
            // Find the referral that belongs to the authenticated user
            $referral = Referral::where('id', $id)
                ->where('from', $request->user()->id)
                ->first();
            
            if (!$referral) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Referral not found or not authorized'
                ], 404);
            }
            
            // Update the record
            $referral->update($request->except(['id', 'from']));
            
            return response()->json([
                'status' => 'success',
                'message' => 'Referral updated successfully',
                'data' => $referral
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update referral',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $id = $request->input('id');
            
            if (!$id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ID is required'
                ], 400);
            }
            
            // Find the referral that belongs to the authenticated user
            $referral = Referral::where('id', $id)
                ->where('from', $request->user()->id)
                ->first();
            
            if (!$referral) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Referral not found or not authorized'
                ], 404);
            }
            
            $referral->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Referral deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete referral',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
