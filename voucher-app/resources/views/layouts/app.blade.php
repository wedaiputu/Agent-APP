<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">
    <!-- Sidebar -->
    <aside class="bg-indigo-700 text-white w-1/4 min-h-full p-6">
        <h2 class="text-xl font-bold mb-4">Agent Dashboard</h2>
        <div class="bg-indigo-800 rounded-lg p-4">
            @yield('sidebar')
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <!-- Navbar Section -->
        <div class="mb-6">
            @yield('navbar')
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            @yield('content')
        </div>
    </main>
</body>
</html>
