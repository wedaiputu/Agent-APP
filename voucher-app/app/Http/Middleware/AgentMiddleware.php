<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentMiddleware
{
    // **
    //  * Handle an incoming request.
    //  *
    //  * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    //  */
    public function handle(Request $request, Closure $next): Response
    {
        dd(Auth::guard('agent')->check());  // Dump if the agent is authenticated

        if (Auth::guard('agent')->check()) {
            return $next($request);  // Allow the request to proceed if the agent is authenticated
        }

        // If the agent is not authenticated, redirect to the agent dashboard
        return redirect()->route('agent.dashboard');
    }
}
