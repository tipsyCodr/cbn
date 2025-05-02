<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Referral;
use App\Models\ThankYou; // TYFCB model
use App\Models\OneToOne; // One-to-one model
use Illuminate\Http\Request;
use Carbon\Carbon;
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
            
            // Calculate time periods
            $now = Carbon::now();
            $startOfWeek = $now->startOfWeek();
            $sixMonthsAgo = $now->copy()->subMonths(6);
            $twelveMonthsAgo = $now->copy()->subMonths(12);
            
            // Referrals stats
            $referralsThisWeek = Referral::where('from', $user->id)
                ->where('created_at', '>=', $startOfWeek)
                ->count();
                
            $referralsSixMonths = Referral::where('from', $user->id)
                ->where('created_at', '>=', $sixMonthsAgo)
                ->count();
                
            $referralsTwelveMonths = Referral::where('from', $user->id)
                ->where('created_at', '>=', $twelveMonthsAgo)
                ->count();
                
            $referralsLifetime = Referral::where('from', $user->id)->count();
            
            // TYFCB stats
            $tyfcbThisWeek = ThankYou::where('from', $user->id)
                ->where('created_at', '>=', $startOfWeek)
                ->count();
                
            $tyfcbSixMonths = ThankYou::where('from', $user->id)
                ->where('created_at', '>=', $sixMonthsAgo)
                ->count();
                
            $tyfcbTwelveMonths = ThankYou::where('from', $user->id)
                ->where('created_at', '>=', $twelveMonthsAgo)
                ->count();
                
            $tyfcbLifetime = ThankYou::where('from', $user->id)->count();
            
            // One-to-One stats
            $otoThisWeek = OneToOne::where('user_id', $user->id)
                ->where('created_at', '>=', $startOfWeek)
                ->count();
                
            $otoSixMonths = OneToOne::where('user_id', $user->id)
                ->where('created_at', '>=', $sixMonthsAgo)
                ->count();
                
            $otoTwelveMonths = OneToOne::where('user_id', $user->id)
                ->where('created_at', '>=', $twelveMonthsAgo)
                ->count();
                
            $otoLifetime = OneToOne::where('user_id', $user->id)->count();
            
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
                    'due_date' => $user->due_date,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'stats' => [
                        'referrals' => [
                            'this_week' => $referralsThisWeek,
                            'six_months' => $referralsSixMonths,
                            'twelve_months' => $referralsTwelveMonths,
                            'lifetime' => $referralsLifetime
                        ],
                        'tyfcb' => [
                            'this_week' => $tyfcbThisWeek,
                            'six_months' => $tyfcbSixMonths,
                            'twelve_months' => $tyfcbTwelveMonths,
                            'lifetime' => $tyfcbLifetime
                        ],
                        'one_to_one' => [
                            'this_week' => $otoThisWeek,
                            'six_months' => $otoSixMonths,
                            'twelve_months' => $otoTwelveMonths,
                            'lifetime' => $otoLifetime
                        ]
                    ]
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
