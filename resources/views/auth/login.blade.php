<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - PINJAMALAT</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 flex items-center justify-center min-h-screen">
    
    <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-xl sm:rounded-xl border border-gray-200 relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-2 bg-indigo-600"></div>

        <div class="text-center mb-8 mt-2">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-wider">
                PINJAM<span class="text-indigo-600">ALAT</span>
            </h1>
            <p class="text-gray-500 mt-2 font-medium">Sistem Manajemen Aset & Peminjaman</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />
        @if ($errors->any())
            <div class="bg-red-50 text-red-600 font-bold p-3 rounded-md mb-4 text-sm border border-red-200">
                Email atau Password salah.
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-5">
                <label for="email" class="block font-bold text-sm text-gray-900 mb-1">Email Address</label>
                <input id="email" class="block w-full border-gray-400 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm text-black px-4 py-2 transition" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@example.com" />
            </div>

            <div class="mb-5">
                <label for="password" class="block font-bold text-sm text-gray-900 mb-1">Password</label>
                <input id="password" class="block w-full border-gray-400 focus:border-indigo-600 focus:ring-indigo-600 rounded-md shadow-sm text-black px-4 py-2 transition" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            </div>

            <div class="flex items-center justify-between mb-8 mt-4">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-400 text-indigo-600 shadow-sm focus:ring-indigo-600" name="remember">
                    <span class="ml-2 text-sm text-gray-600 font-medium">Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3 px-4 rounded-md transition duration-150 border border-black shadow-md tracking-wide">
                LOG IN
            </button>
        </form>
    </div>

</body>
</html>