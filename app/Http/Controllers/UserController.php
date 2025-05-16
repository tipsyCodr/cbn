<?php

namespace App\Http\Controllers;

use App\Models\PaymentDetails;
use App\Notifications\MemberJoined;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Validator;
use Illuminate\View\View;
use App\Models\User;

class UserController extends Controller
{
    public function form(): View
    {
        return view('membership');
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request):RedirectResponse{
        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'company' => 'sometimes|nullable|string|max:255',
            'category' => 'sometimes|nullable|string|max:255',
            'bio' => 'sometimes|nullable|string|max:500',
            'payment_details' => 'required|string|max:255',
            'payment_screenshot' => 'required|file|image|max:5120', // 5MB max, image file required
            'other_details' => 'nullable|string|max:255',
            'mobile' => 'required|string|max:15',
        ]);

        $firstName = explode(' ', $validate['name'])[0];
        $un_en_password = 'cbn@'.strtolower($firstName).'@0987';

        // Handle file upload
        $screenshotPath = null;
        if ($request->hasFile('payment_screenshot')) {
            $file = $request->file('payment_screenshot');
            $fileName = 'payment_' . time() . '_' . $request->name . '.' . $file->getClientOriginalExtension();
            $screenshotPath = $file->storeAs('payment_screenshots', $fileName, 'public');
        }

        $user = User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'company' => $validate['company'] ?? null,
            'category' => $validate['category'] ?? null,
            'bio' => $validate['bio'] ?? null,
            'mobile' => $validate['mobile'],
            'password' => Hash::make($un_en_password),
        ]);

        $paymentDetails = new PaymentDetails();
        $paymentDetails->user_id = $user->id;
        $paymentDetails->payment_details = $validate['payment_details'];
        $paymentDetails->payment_screenshot = $screenshotPath;
        $paymentDetails->other_details = $validate['other_details'] ?? null;
        $paymentDetails->save();
        
        $user->notify(new MemberJoined($user, $un_en_password));

        session()->flash('success', 'Membership created successfully. You will receive login details via email.');
        return redirect()->back()->with('success', 'Membership created successfully. You will receive login details via email.');
    }
}
