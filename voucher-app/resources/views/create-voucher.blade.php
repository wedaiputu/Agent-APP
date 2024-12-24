<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Voucher</title>
</head>
<body>
    <h1>Create a New Voucher</h1>
    <h2>Router {{ $routerName }}</h2>
    <a href="{{ route('voucher.list') }}">Back to Voucher List</a>
    <hr>

    <form method="POST" action="{{ route('create.voucher') }}">
        @csrf
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>

        <label for="password">Password:</label>
        <input type="text" id="password" name="password" required>
        <br>

        <label for="limit">Limit (e.g., 1h, 2h):</label>
        <input type="text" id="limit" name="limit">
        <br>

        <button type="submit">Create Voucher</button>
    </form>
</body>
</html>
