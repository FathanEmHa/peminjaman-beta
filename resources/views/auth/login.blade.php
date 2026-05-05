<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SIPA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
</head>
<body class="antialiased flex items-center justify-center min-h-screen relative font-['Inter']" 
      style="background-image: url('https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=2000&auto=format&fit=crop'); background-size: cover; background-position: center;">

    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

    <div class="w-full sm:max-w-md px-8 py-10 bg-white shadow-2xl sm:rounded-2xl border border-white/20 relative z-10 overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 to-indigo-800"></div>

        <div class="text-center mb-10 mt-2">
            <div class="flex justify-center mb-3">
                <div class="p-3 bg-indigo-50 rounded-2xl shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tighter">
                SIPA<span class="text-indigo-600 font-bold text-5xl leading-none">.</span>
            </h1>
            <p class="text-gray-500 mt-2 text-xs font-bold tracking-widest uppercase">Sistem Peminjaman Alat</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />
        
        @if ($errors->any())
            <div class="flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-xl mb-6 text-sm font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Email atau Password salah.
            </div>
        @endif

        {{-- Tambahin autocomplete="off" di tag form --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-5" autocomplete="off">
            @csrf

            <div>
                <label for="email" class="block font-semibold text-sm text-gray-700 mb-1.5">Email Address</label>
                {{-- Tambahin autocomplete="new-password" buat ngecoh browser --}}
                <input id="email" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-gray-900 px-4 py-2.5 transition bg-gray-50 focus:bg-white outline-none" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="new-password" placeholder="admin@example.com" />
            </div>

            <div>
                <label for="password" class="block font-semibold text-sm text-gray-700 mb-1.5">Password</label>
                {{-- Tambahin autocomplete="new-password" --}}
                <input id="password" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm text-gray-900 px-4 py-2.5 transition bg-gray-50 focus:bg-white outline-none" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            </div>

            <div class="flex items-center justify-between pt-2 pb-4">
                <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4 transition" name="remember">
                    <span class="ml-2 text-sm text-gray-500 font-medium group-hover:text-gray-800 transition">Ingat Saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-gray-900/20 active:scale-[0.98] flex justify-center items-center gap-2">
                Masuk ke Sistem
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>
    </div>

</body>
</html>