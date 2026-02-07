<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Pulsa Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-white">🔒 Admin Console</h1>
            <p class="text-gray-400 mt-1 text-sm">Pulsa Pro Management</p>
        </div>
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-lg font-bold text-gray-800 mb-6">Admin Login</h2>
            @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="/console/login" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" name="username" required autofocus class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 text-sm">
                </div>
                <button type="submit" class="w-full bg-gray-900 text-white py-2.5 rounded-lg font-semibold text-sm hover:bg-gray-800 transition">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
