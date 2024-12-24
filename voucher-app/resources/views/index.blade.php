@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Vouchers</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Password</th>
                <th>Price</th>
                <th>Uptime Limit</th>
                <th>Barcode</th>
                <th>Agent Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vouchers as $voucher)
                <tr>
                    <td>{{ $voucher->username }}</td>
                    <td>{{ $voucher->password }}</td>
                    <td>{{ $voucher->price }}</td>
                    <td>{{ $voucher->uptime_limit }} minutes</td>
                    <td>{{ $voucher->barcode }}</td>
                    <td>{{ $voucher->agent_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
