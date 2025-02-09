<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
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
            'password' => ['required']
        ]);
    
        if (!Auth::attempt($attributes)){
            return response()->json(['message' => 'Credentials do not match'], 401);
        }
        
        return $request->user()->createToken('API TOKEN')->plainTextToken;
    }

    /**
     * Logout logic
     * 
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function destroy()
    {
        if (session('user')) {
            Session::remove('user');

            return response()->json([], 200);
        } else {
            return response()->json([], 404);
        }
    }
}
