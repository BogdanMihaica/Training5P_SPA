<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\returnSelf;

class SessionController extends Controller
{
    /**
     * View the login page
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('login');
    }

    /**
     * Log in the user ifthe credentials get validated
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        $user = config('user.user');

        if (
            $user['username'] != $request->input('username') ||
            !Hash::check($request->input('password'), $user['password'])
        ) {
            return redirect()->back()->withErrors(['match-error' => 'Credentials don\'t match!']);
        }

        session(['user' => $user['username']]);

        return redirect()->route('products.dashboard');
    }

    /**
     * Logout logic
     * 
     * @return mixed|\Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        if (session('user')) {
            session()->remove('user');

            return redirect()->route('login');
        } else {
            return redirect()->back();
        }
    }
}
