<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HCP Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4 shadow-lg">
        <div class="container mx-auto flex justify-between">
            <a href="/" class="text-xl font-bold">HCP Dashboard</a>
            <div>
                <a href="{{ route('servers.index') }}" class="hover:underline">Server</a>
            </div>
        </div>
    </nav>
    <main class="container mx-auto mt-8 p-4">
        @yield('content')
    </main>
</body>
</html>
