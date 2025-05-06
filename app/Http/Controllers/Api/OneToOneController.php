<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OneToOne; // Import the OneToOne model
use Illuminate\Http\Request;
use App\Models\User; // Import the User model

class OneToOneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $otoRequests = OneToOne::all(); // Fetch OneToOne requests
        return response()->json(['oto_requests' => $otoRequests], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       try { 
           $data = $request->all(); // Get all request data
           $data['user_id'] = $request->user()->id; // Set the user ID

           $otoRequest = OneToOne::create($data); // Create a new OneToOne request

           return response()->json(['oto_request' => $otoRequest], 201);
       } catch (\Exception $e) {
           return response()->json(['error' => $e->getMessage()], 500);
       }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        try {
            // Find the OneToOne request that belongs to the authenticated user
            $otoRequest = OneToOne::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->first();
            
            if (!$otoRequest) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'OneToOne request not found or not authorized'
                ], 404);
            }
            
            // Get the user details
            $user = User::find($otoRequest->user_id); // Assuming 'user_id' is the user ID

            return response()->json([
                'status' => 'success',
                'data' => [
                    'oto_request' => $otoRequest,
                    'user_name' => $user ? $user->name : null // Include user name
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch OneToOne request',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OneToOne $otoRequest)
    {
        $otoRequest->update($request->all()); // Update the OneToOne request
        return response()->json(['oto_request' => $otoRequest], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OneToOne $otoRequest)
    {
        $otoRequest->delete(); // Delete the OneToOne request
        return response()->json(['message' => 'OneToOne request deleted successfully'], 200);
    }
}
