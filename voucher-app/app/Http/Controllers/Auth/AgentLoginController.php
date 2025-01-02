<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Agent;

class AgentLoginController extends Controller
{
    /**
     * Show the login form.
     */
    
    public function showLoginForm()
    {
        return view('auth.agent-login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
{
    // $agent = Agent::find(1);  // Or any other query

    // dd($agent);
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:3',
    ]);

    // dd(Auth::guard('agent')->check()); 

    // Attempt to log the agent in
    if (Auth::guard('agent')->attempt($request->only('email', 'password'))) {
        return redirect()->intended(route('agent.dashboard'));
    }

    return back()->withErrors([
        'email' => 'Invalid credentials.',
    ])->withInput();
}


    /**
     * Logout the agent.
     */
    public function logout(Request $request)
    {
        Auth::guard('agent')->logout();
        return redirect()->route('agent.login');
    }
}
