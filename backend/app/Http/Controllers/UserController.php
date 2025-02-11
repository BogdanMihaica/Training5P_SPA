<?php

namespace App\Http\Controllers;

use App\Rules\CredentialsMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            'password' => ['required'],
        ]);
        
        $loggedIn = Auth::attempt($attributes);

        Validator::make(['credentials' => $loggedIn], [
            'credentials' => ['accepted'],
        ],['accepted' => 'Credentials don\'t match'])->validate();

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
