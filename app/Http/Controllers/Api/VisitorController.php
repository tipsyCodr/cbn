<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Visitor;
use App\Notifications\VisitorRegistered;

use Illuminate\Http\Request;


class VisitorController extends Controller
{
    /**
     * Store the Visition form Data.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);

        $visitor = Visitor::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'company' => $request->company,
            'position' => $request->position,
            'status' => 'registered', // Assuming a default status
        ]);
        $visitor->notify(new VisitorRegistered($visitor));

        return response()->json('Visitor registered successfully.', 200);
    }
}
