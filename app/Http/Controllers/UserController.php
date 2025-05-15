<?php

namespace App\Http\Controllers;

use App\Notifications\MemberJoined;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
//            'password' => 'required|string|min:8|confirmed',
        ]);

        $firstName = explode(' ', $validate['name'])[0];
        $un_en_password = 'cbn@'.strtolower($firstName).'@0987';

        $user = User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'company' => $validate['company'] ?? null,
            'category' => $validate['category'] ?? null,
            'bio' => $validate['bio'] ?? null,
            'password' => Hash::make($un_en_password),
        ]);

        $user->notify(new MemberJoined($user, $un_en_password));

        session()->flash('success', 'Membership created successfully');
        return redirect()->back()->with('sucess', 'Membership created successfully');
    }

}
