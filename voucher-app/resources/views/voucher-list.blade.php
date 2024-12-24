<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher List</title>
</head>
<body>
    <h1>Voucher List</h1>
    {{-- <h2>Router: {{[ $routerName ] }}</h2> --}}
    <a href="{{ route('logout') }}">Logout</a>
    <a href="{{ route('create.voucher') }}">Create Voucher</a>
    <hr>

    @if(isset($vouchers) && count($vouchers) > 0)
        <table border="1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Password</th>
                    <th>Limit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vouchers as $index => $voucher)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $voucher['name'] ?? '-' }}</td>
                        <td>{{ $voucher['password'] ?? '-' }}</td>
                        <td>{{ $voucher['limit-uptime'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No vouchers found.</p>
    @endif
</body>
</html>
