<?php

namespace App\Http\Controllers;

use App\Models\RouterosAPI;
use App\Http\Requests\UpdateVoucherRequest;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::all();

        return response()->json([
            'success' => true,
            'data' => $vouchers,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // Validate the input data
    $validated = $request->validate([
        'quantity' => 'required|integer|min:1',
        'price' => 'required|numeric|min:1',
        'uptime_limit' => 'required|integer|min:1',
        'barcode' => 'required|string',
        'agent_name' => 'required|string',
    ]);

    // Router credentials
    $ip = '192.168.30.1';
    $user = 'test';
    $pass = 'test';

    // Initialize RouterosAPI
    $API = new RouterosAPI();
    $API->debug(false);

    if ($API->connect($ip, $user, $pass)) {
        $vouchers = [];
        for ($i = 0; $i < $validated['quantity']; $i++) {
            $username = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);
            $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);

            // API call to add user
            $response = $API->comm('/ip/hotspot/user/add', [
                'name' => $username,
                'password' => $password,
                'profile' => 'default',
                'limit-uptime' => "{$validated['uptime_limit']}m",
            ]);

            // Handle error in API response
            if (isset($response['!trap'])) {
                continue; // Skip failed voucher creation
            }

            // Create voucher in the database
            $voucher = Voucher::create([
                'quantity' => $validated['quantity'],
                'username' => $username,
                'password' => $password,
                'price' => $validated['price'],
                'uptime_limit' => $validated['uptime_limit'],
                'barcode' => $validated['barcode'],
                'agent_name' => $validated['agent_name'],
            ]);

            $vouchers[] = $voucher;

            // Optional: Add a small delay between API calls
            usleep(100000);  // Delay for 0.1 second
        }

        $API->disconnect();

        // Return response with success message
        return redirect()->route('vouchers.create')->with('success', 'Vouchers generated successfully!');
    }

    // In case of connection failure
    return redirect()->route('vouchers.create')->with('error', 'Failed to connect to MikroTik router.');
}

    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $voucher = Voucher::find($id);

    if (!$voucher) {
        return response()->json([
            'success' => false,
            'message' => 'Voucher not found',
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $voucher,
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoucherRequest $request, Voucher $voucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        //
    }
}
