<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    
    <form method="POST" action="{{ route('mikrotik.mikrotiklogin') }}">
        @csrf
        <h1>Login</h1>
        <label for="ip">IP Address</label>
        <input type="text" name="ip" placeholder="Enter IP Address" required>
        <label for="username">Username</label>
        <input type="text" name="username" placeholder="Enter Username" required>
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit">Login</button>

        @if ($errors->any())
            <div>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </form>
</body>
</html>
