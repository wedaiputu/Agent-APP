<?php

namespace App\Http\Controllers;

use App\Models\RouterosAPI;
use Illuminate\Http\Request;

class MikrotikController extends Controller
{
    public function showLoginForm()
    {
        // Display the login form
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'ip' => 'required|ip',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $api = new RouterosAPI();
        $api->debug = false;

        $ip = $request->input('ip');
        $username = $request->input('username');
        $password = $request->input('password');

        if ($api->connect($ip, $username, $password)) {
            // Store connection details in the session
            session([
                'ip' => $ip,
                'username' => $username,
                'password' => $password,
            ]);

            // Disconnect the API session after storing the session data
            $api->disconnect();

            // Redirect to the voucher list page
            return redirect()->route('voucher.list');
        } else {
            // If authentication fails, redirect back with an error
            return back()->withErrors(['login_error' => 'Failed to connect to the MikroTik router. Check your credentials.']);
        }
    }

    

    public function createVoucher()
{
    if (!session('ip')) {
        return redirect()->route('login');
    }

    $api = new RouterosAPI();
    $api->debug = false;

    // $routerName = 'Unknown Router'; // Default value

    if ($api->connect(session('ip'), session('username'), session('password'))) {
        $identity = $api->comm('/system/identity/print');
        if (!empty($identity[0]['name'])) {
            $routerName = $identity[0]['name'];
        }
        $api->disconnect();
    }

    // Store the router name in the session
    session(['routerName' => $routerName]);
    dd($routerName, 'jkniqnweiouihqwbneoqwe ');

    return view('create-voucher', compact('routerName'));
}



public function voucherList()
{
    
    if (!session('ip')) {
        return redirect()->route('login');
    }
    $routerName = session('routerName');
    // dd(session('routerName'));


    $api = new RouterosAPI();
    $api->debug = false;

    if ($api->connect(session('ip'), session('username'), session('password'))) {
        $vouchers = $api->comm('/ip/hotspot/user/print');
        $api->disconnect();

        return view('voucher-list', compact('vouchers', 'routerName'));
    }

    return redirect()->route('login')->withErrors(['error' => 'Failed to retrieve vouchers.']);
}


    public function logout()
    {
        // Clear the session
        session()->flush();

        // Redirect to the login page
        return redirect()->route('login');
    }
}
