<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class StoreVoucherController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'price' => 'required|integer',
            'uptime_limit' => 'required|integer',
            'barcode' => 'required|string',
            'agent_name' => 'required|string',
        ]);

        // Create a new voucher entry in the database
        $voucher = Voucher::create([
            'username' => $validated['username'],
            'password' => $validated['password'],
            'price' => $validated['price'],
            'uptime_limit' => $validated['uptime_limit'],
            'barcode' => $validated['barcode'],
            'agent_name' => $validated['agent_name'],
        ]);

        // Redirect back with a success message
        return back()->with('success', 'Voucher successfully stored!');
    }
}
