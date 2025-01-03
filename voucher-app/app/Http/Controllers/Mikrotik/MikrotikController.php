<?php

namespace App\Http\Controllers\Mikrotik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RouterosAPI;
use App\Models\Voucher;

class MikrotikController extends Controller
{
    public function index()
    {
        // dd("masuk");
        return view('mikrotik.dashboard');
    }

    public function mikrotikLogin(Request $request)
    {
        $request->validate([
            'ip' => 'required|ip',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        // dd("masuk");
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
        // Check if IP session exists, otherwise redirect to login
        if (!session('ip')) {
            return redirect()->route('login');
        }

        $api = new RouterosAPI();
        $api->debug = false;

        if ($api->connect(session('ip'), session('username'), session('password'))) {
            $identity = $api->comm('/system/identity/print');
            if (!empty($identity[0]['name'])) {
                $routerName = $identity[0]['name'];
            }
            $api->disconnect();
        }

        // Store routerName in the session
        session()->put('routerName', $routerName);

        // Debug session data
        // dd(session('routerName')); 

        return view('create-voucher', compact('routerName'));
    }
    private function connectToMikrotik()
    {
        $api = new RouterosAPI();
        $api->debug = false;

        if ($api->connect(session('ip'), session('username'), session('password'))) {
            return $api;
        }
        return null;
    }


    public function voucherList()
{
    // Check if IP session exists, otherwise redirect to login
    if (!session('ip')) {
        return redirect()->route('login');
    }

    $api = new RouterosAPI();
    $api->debug = false;

    // Retrieve agent details from the session
    $agentId = session('agent_id');
    $agentName = session('agent_name');
    $mikrotikIp = session('mikrotik_ip');
    $mikrotikUsername = session('mikrotik_username');
    $mikrotikPassword = session('mikrotik_password');
    $routerName = session('routerName');

    if ($api->connect(session('ip'), session('username'), session('password'))) {
        // Fetch all vouchers from MikroTik
        $vouchers = $api->comm('/ip/hotspot/user/print');
        $api->disconnect();

        foreach ($vouchers as &$voucher) {
            // Set the voucher status based on the 'disabled' field
            $voucher['status'] = $voucher['disabled'] === 'false' ? 'active' : 'inactive';
        }

        // Filter agent vouchers
        $agentVouchers = array_filter($vouchers, function ($voucher) {
            return isset($voucher['comment']) && $voucher['comment'] === 'agent';
        });

        // Save agent vouchers to the database
        foreach ($agentVouchers as $agentVoucher) {
            Voucher::updateOrCreate(
                ['username' => $agentVoucher['name'], 'agent_id' => $agentId], // Match on username and agent_id
                [
                    'password' => $agentVoucher['password'] ?? null,
                    'status' => $agentVoucher['status'],
                    'limit_uptime' => $agentVoucher['limit-uptime'] ?? null,
                    'price' => $agentVoucher['price'] ?? 1000,
                    'profile' => $agentVoucher['profile'] ?? 'default',
                    'comment' => $agentVoucher['comment'],
                ]
            );
        }

        // Separate non-agent vouchers
        $nonAgentVouchers = array_filter($vouchers, function ($voucher) {
            return !isset($voucher['comment']) || $voucher['comment'] !== 'agent';
        });

        // Further categorize non-agent vouchers by profile (default vs custom)
        $defaultProfileVouchers = array_filter($nonAgentVouchers, function ($voucher) {
            return isset($voucher['profile']) && $voucher['profile'] === 'default';
        });

        $customProfileVouchers = array_filter($nonAgentVouchers, function ($voucher) {
            return isset($voucher['profile']) && $voucher['profile'] !== 'default';
        });

        // Count the vouchers in each category
        $totalAgentVouchers = count($agentVouchers);
        $totalNonAgentVouchers = count($nonAgentVouchers);
        $totalDefaultProfileVouchers = count($defaultProfileVouchers);
        $totalCustomProfileVouchers = count($customProfileVouchers);

        // Pass all vouchers, counts, and agent details to the view
        return view('voucher-list', compact(
            'vouchers',
            'routerName',
            'agentId',
            'agentName',
            'agentVouchers',
            'nonAgentVouchers',
            'defaultProfileVouchers',
            'customProfileVouchers',
            'totalAgentVouchers',
            'totalNonAgentVouchers',
            'totalDefaultProfileVouchers',
            'totalCustomProfileVouchers'
        ));
    }

    return redirect()->route('login')->withErrors(['error' => 'Failed to retrieve vouchers.']);
}



    // return redirect()->route('login')->withErrors(['error' => 'Failed to retrieve vouchers.']);

    public function updateVoucherComment(Request $request)
    {
        $voucherId = $request->input('voucherId');
        $newComment = $request->input('comment');

        // Check if IP session exists
        if (!session('ip')) {
            return response()->json(['error' => 'IP session not found'], 400);
        }

        $api = new RouterosAPI();
        $api->debug = false;

        if ($api->connect(session('ip'), session('username'), session('password'))) {
            // Update the comment field for the specified voucher ID
            $response = $api->comm('/ip/hotspot/user/set', [
                '.id' => $voucherId,  // The voucher ID to update
                'comment' => $newComment,  // New comment value
            ]);
            $api->disconnect();

            return response()->json(['message' => 'Voucher comment updated successfully']);
        }

        return response()->json(['error' => 'Failed to connect to MikroTik'], 500);
    }


    public function logout()
    {
        // Clear the session
        session()->flush();

        // Redirect to the login page
        return redirect()->route('login');
    }
}
