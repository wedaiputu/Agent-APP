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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ]);
    
        // Attempt to log the agent in
        if (Auth::guard('agent')->attempt($request->only('email', 'password'))) {
            // Retrieve the authenticated agent
            $agent = Auth::guard('agent')->user();
    
            // Store agent-related information in the session
            session([
                'agent_id' => $agent->id,            // Save agent ID in session
                'agent_name' => $agent->name,        // Optional: Save agent name
                'routerName' => $agent->router_name, // Optional: Save router name if available in database
            ]);
    
            return redirect()->route('agent.dashboard');
        }
    
        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
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
