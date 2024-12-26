<?php

namespace App\Http\Controllers;

use App\Models\RouterosAPI;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\Agent;

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


    public function voucherList()
    {
        // Check if IP session exists, otherwise redirect to login
        if (!session('ip')) {
            return redirect()->route('login');
        }

        $api = new RouterosAPI();
        $api->debug = false;

        // Retrieve the routerName from session
        $routerName = session('routerName');

        if ($api->connect(session('ip'), session('username'), session('password'))) {
            $vouchers = $api->comm('/ip/hotspot/user/print');
            $api->disconnect();

            // Initialize categories for 'default' and 'other than default'
            $categories = [
                'default' => [],
                'other than default' => []
            ];

            // Initialize subcategories for limit-uptime ranges
            $timeCategories = [
                '1 hour' => [],
                '2 hours' => [],
                '3 hours' => [],
                '5 hours' => [],
                '8 hours' => [],
                '12 hours' => [],
                '24 hours' => []
            ];

            // Classify vouchers by profile and then by limit-uptime
            foreach ($vouchers as $voucher) {
                // Classify by profile
                $profile = $voucher['profile'] ?? 'other than default';
                $categories[$profile][] = $voucher;

                // Classify by limit-uptime range
                $limitUptime = $voucher['limit-uptime'] ?? '0';
                $hours = (int) filter_var($limitUptime, FILTER_SANITIZE_NUMBER_INT);

                if ($hours < 2) {
                    $timeCategories['1 hour'][] = $voucher;
                } elseif ($hours >= 2 && $hours < 3) {
                    $timeCategories['2 hours'][] = $voucher;
                } elseif ($hours >= 3 && $hours < 5) {
                    $timeCategories['3 hours'][] = $voucher;
                } elseif ($hours >= 5 && $hours < 8) {
                    $timeCategories['5 hours'][] = $voucher;
                } elseif ($hours >= 8 && $hours < 12) {
                    $timeCategories['8 hours'][] = $voucher;
                } elseif ($hours >= 12 && $hours < 24) {
                    $timeCategories['12 hours'][] = $voucher;
                } elseif ($hours >= 24) {
                    $timeCategories['24 hours'][] = $voucher;
                }
            }

            return view('voucher-list', compact('categories', 'timeCategories', 'routerName'));
        }

        // In case of failure to connect
        return redirect()->route('login')->withErrors(['error' => 'Failed to retrieve vouchers.']);
    }


    public function logout()
    {
        // Clear the session
        session()->flush();

        // Redirect to the login page
        return redirect()->route('login');
    }

    public function sellVoucher(Request $request)
{
    $validated = $request->validate([
        'profile' => 'required|string',
        'subcategory' => 'required|string',
        'quantity' => 'required|integer|min:1',
    ]);

    $profile = $validated['profile'];
    $subcategory = $validated['subcategory'];
    $quantity = $validated['quantity'];

    $api = new RouterosAPI();
    $api->debug = false;

    if (!$api->connect(session('ip'), session('username'), session('password'))) {
        return response()->json(['message' => 'Failed to connect to MikroTik router.'], 500);
    }

    // Fetch vouchers from MikroTik for the given profile and subcategory
    $vouchers = $api->comm('/ip/hotspot/user/print', [
        '?profile' => $profile,
        '?limit-uptime' => $subcategory, // Assuming subcategory maps to limit-uptime
    ]);

    $soldVouchers = [];

    // Mark the required quantity of vouchers as sold
    for ($i = 0; $i < $quantity && $i < count($vouchers); $i++) {
        $voucher = $vouchers[$i];

        // Update the voucher status in MikroTik
        $api->comm('/ip/hotspot/user/set', [
            '.id' => $voucher['.id'],
            'disabled' => 'true', // Mark as sold by disabling
        ]);

        // Save the voucher details to the database
        $soldVoucher = Voucher::create([
            'username' => $voucher['name'],
            'password' => $voucher['password'],
            'profile' => $profile,
            'subcategory' => $subcategory,
        ]);

        $soldVouchers[] = $soldVoucher;
    }

    $api->disconnect();

    // Render the updated stored voucher container
    $storedVouchers = Voucher::all();
    $html = view('partials.stored-vouchers', compact('storedVouchers'))->render();

    return response()->json(['html' => $html]);
}
}
