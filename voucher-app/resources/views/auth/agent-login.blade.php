<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Login</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto max-w-md mt-20">
        <div class="bg-white p-6 rounded shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-center">Agent Login</h2>
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('agent.login.submit') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="w-full border-gray-300 rounded px-3 py-2" value="{{ old('email') }}" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="w-full border-gray-300 rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-700">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
