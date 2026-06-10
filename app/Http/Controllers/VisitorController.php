<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Notifications\VisitorRegistered;
use Illuminate\Http\Request;
use App\Models\Visitor;
class VisitorController extends Controller
{
    public function index()
    {
        return view('visitor');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'invite_code' => 'nullable|string|exists:users,invite_code',
        ]);

        $referrer = null;
        if ($request->filled('invite_code')) {
            $referrer = \App\Models\User::where('invite_code', $request->invite_code)->first();
        }

        $visitor = Visitor::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'company' => $request->company,
            'position' => $request->position,
            'status' => 'registered', // Assuming a default status
            'invited_by' => $referrer ? $referrer->id : null,
        ]);
        $visitor->notify(new VisitorRegistered($visitor));

        return redirect()->back()->with('success', 'Visitor registered successfully.');
    }

    public function getUserByInviteCode($code)
    {
        $user = \App\Models\User::where('invite_code', $code)->first();
        if ($user) {
            return response()->json(['success' => true, 'name' => $user->name]);
        }
        return response()->json(['success' => false, 'message' => 'Invalid code'], 404);
    }
}
