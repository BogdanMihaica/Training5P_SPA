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
        try{
            $validateUser = Validator::make($request->all(), [
                'email' => ['required'],
                'password' => ['required']
            ]);
    
            if($validateUser->fails()) {
                return response()->json([
                    'message'=> 'Validation error',
                    'errors' => $validateUser->errors()
                ],401);
            }
    
            if(!Auth::attempt($request->only('email', 'password'))){
                return response()->json([
                    'message'=> 'Email or password does not match',
                    'errors' => $validateUser->errors()
                ],401);
            }

    
            $user = User::where('email', $request->email)->first();
            $request->session()->regenerate();

            return response()->json([
                'message'=> 'User logged in successfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }

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
