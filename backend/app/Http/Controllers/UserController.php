<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Log in the user if the credentials get validated
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);
    
        if (!Auth::attempt($attributes)){
            return response()->json(['message' => 'Credentials do not match'], 401);
        }
        
        $token = $request->user()->createToken('API TOKEN')->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }

    /**
     * Logout logic
     * 
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        Auth::guard('web')->logout();

        return response()->json();
    }
}
