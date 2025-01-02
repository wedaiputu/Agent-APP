<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent; // Ensure you have the Agent model created

class DashboardController extends Controller
{
    // Function to display the agent dashboard
    public function index()
    {
        // Fetch the currently authenticated agent
        $agent = auth('agent')->user();
    
        // Pass the agent data to the view
        return view('agent.dashboard', compact('agent'));
    }
    public function logout()
    {
        // Log the agent out
        Auth::guard('agent')->logout();

        // Redirect to login page after logout
        return redirect()->route('agent.login');
    }
    
}
