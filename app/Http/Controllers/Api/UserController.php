<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('active', 1)->where('role', 'user')->get();
        return response()->json(['users' => $users], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Store the user
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'mobile' => 'nullable|string|max:15|unique:users',
            'email' => 'nullable|email|unique:users',
            'occupation' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email_verified_at' => 'nullable|date',
            'profile_photo' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }

        $user = User::create($request->all());

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json(['user' => $user], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        return response()->json(['message' => 'User deleted successfully', 'user' => $user], 200);
    }

    /**
     * Fetch authenticated user's details
     */
    public function home(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }
            
            // Return user details
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'occupation' => $user->occupation,
                    'department' => $user->department,
                    'category' => $user->category,
                    'designation' => $user->designation,
                    'company' => $user->company,
                    'address' => $user->address,
                    'profile_photo' => $user->profile_photo,
                    'bio' => $user->bio,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch user details',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
