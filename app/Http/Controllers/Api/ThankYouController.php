<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ThankYou;
use Illuminate\Http\Request;
class ThankYouController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Get thank you records for the authenticated user
            $thankYou = ThankYou::with('user:id,name')->where('from', $request->user()->id)->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $thankYou
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch thank you requests',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            // Merge the authenticated user ID with the request data
            $data = $request->all();
            $data['from'] = $request->user()->id;
            
            $thankYou = ThankYou::create($data);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Thank you request created successfully',
                'data' => $thankYou
            ], 201);
        } catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create thank you request',
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
           $thankYou = ThankYou::where('id', $id)
                ->orWhere('from', $request->user()->id)
                ->orWhere('to', $request->user()->id)
                ->first();
           
           if (!$thankYou) {
               return response()->json([
                   'status' => 'error',
                   'message' => 'Thank You request not found'
               ], 404);
           }
           
           return response()->json([
               'status' => 'success',
               'data' => $thankYou
           ], 200);
       } catch(\Exception $e){
           return response()->json([
               'status' => 'error',
               'message' => 'Failed to fetch thank you request',
               'error' => $e->getMessage()
           ], 500);
       }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try{
            // Get the id from the request
            $id = $request->input('id');
            
            if (!$id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ID is required'
                ], 400);
            }
            
            // Find the thank you record that belongs to the authenticated user
            $thankYou = ThankYou::where('id', $id)
                ->where('from', $request->user()->id)
                ->first();
            
            if(!$thankYou){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Thank You request not found or not authorized'
                ], 404);
            }
            
            // Update the record
            $thankYou->update($request->except(['id', 'user_id']));
            
            return response()->json([
                'status' => 'success',
                'message' => 'Thank You request updated successfully',
                'data' => $thankYou
            ], 200);
        }
        catch(\Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update Thank You request',
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
            
            // Find the thank you record that belongs to the authenticated user
            $thankYou = ThankYou::where('id', $id)
                ->where('from', $request->user()->id)
                ->first();
            
            if (!$thankYou) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Thank You request not found or not authorized'
                ], 404);
            }
            
            $thankYou->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Thank You Request deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete Thank You request',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
