@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Display Agent Details -->
    <h1 class="text-2xl font-bold">Agent: {{ $agentName }} (ID: {{ $agentId }})</h1>
    <h2 class="text-lg font-semibold text-gray-600">Nama Router: {{ $routerName }}</h2>

    <!-- Card Layout for Voucher Counts -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
        <!-- Agent Voucher Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800">Agent Vouchers</h2>
            <p class="text-2xl font-bold text-gray-600">{{ $totalAgentVouchers }}</p>
        </div>

        <!-- Non-Agent Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800">Non-Agent</h2>
            <p class="text-2xl font-bold text-gray-600">{{ $totalNonAgentVouchers }}</p>
        </div>

        <!-- Default Profile Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800">Default Profile</h2>
            <p class="text-2xl font-bold text-gray-600">{{ $totalDefaultProfileVouchers }}</p>
        </div>

        <!-- Custom Profile Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800">Custom Profile</h2>
            <p class="text-2xl font-bold text-gray-600">{{ $totalCustomProfileVouchers }}</p>
        </div>
    </div>

    <!-- Agent Vouchers Details -->
    <h2 class="mt-6 text-2xl font-bold text-gray-800">List Vouchers yang dimiliki</h2>
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800">Agent Vouchers</h2>
        <p class="text-2xl font-bold text-gray-600">{{ $totalAgentVouchers }}</p>
    </div>
    @if (count($agentVouchers) > 0)
        <table class="min-w-full mt-4 border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border p-2 text-left">ID</th>
                    <th class="border p-2 text-left">Name</th>
                    <th class="border p-2 text-left">Password</th>
                    <th class="border p-2 text-left">Status</th>
                    <th class="border p-2 text-left">Profile</th>
                    <th class="border p-2 text-left">Address</th>
                    <!-- Add other columns as needed -->
                </tr>
            </thead>
            <tbody>
                @foreach ($agentVouchers as $voucher)
                    <tr>
                        <td class="border p-2">{{ $voucher['.id'] }}</td>
                        <td class="border p-2">{{ $voucher['name'] ?? 'N/A' }}</td>
                        <td class="border p-2">{{ $voucher['password'] ?? 'N/A' }}</td>
                        <td class="border p-2">{{ $voucher['status'] }}</td>
                        <td class="border p-2">{{ $voucher['profile'] ?? 'N/A' }}</td>
                        <td class="border p-2">{{ $voucher['address'] ?? 'N/A' }}</td>
                        <!-- Add other columns as needed -->
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No agent vouchers available.</p>
    @endif
</div>
@endsection
