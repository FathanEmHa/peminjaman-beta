<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PinjamAlat') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-100 flex h-screen overflow-hidden">
        
        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col overflow-y-auto">
            
            <nav class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-8 shrink-0 sticky top-0 z-10 shadow-sm">
                
                <div class="flex items-center text-gray-800">
                    @isset($header)
                        {{ $header }}
                    @endisset
                </div>

                <div class="flex items-center space-x-6">
                    <div class="text-right">
                        <div class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</div>
                        <div class="text-xs font-bold text-indigo-600 uppercase tracking-wider">{{ auth()->user()->role }}</div>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 font-bold py-1.5 px-4 rounded-md text-sm transition">
                            Logout
                        </button>
                    </form>
                </div>
            </nav>

            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
</html>