<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Rules\BangladeshPhone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = \Illuminate\Support\Facades\View::exists("frontend.{$theme}.profile.edit") ? "frontend.{$theme}.profile.edit" : 'frontend.profile.edit';
        return view($view);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => ['nullable', 'string', 'max:30', new BangladeshPhone],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = BangladeshPhone::normalize($validated['phone'] ?? null);

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }
}

