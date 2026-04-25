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

        <div class="flex-1 flex flex-col overflow-y-auto relative">
            
            {{-- Hapus 'sticky top-0' biar navbarnya ikut ke-scroll ke atas --}}
            <nav class="bg-white/80 backdrop-blur-md border-b border-gray-200 h-16 flex items-center justify-between px-8 shrink-0 z-0 shadow-sm">
                
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
                    
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="m-0">
                        @csrf
                        
                        {{-- Kita akalin pake Alpine x-data --}}
                        <div x-data="{ showLogoutModal: false }" class="inline-block">
                            
                            {{-- Tombol Pemicu --}}
                            <button type="button" @click="showLogoutModal = true" class="bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 font-bold py-1.5 px-4 rounded-md text-sm transition">
                                Logout
                            </button>

                            {{-- JURUS JITU: x-teleport="body" biar modal keluar dari penjara navbar --}}
                            <template x-teleport="body">
                                <div x-show="showLogoutModal" style="display: none;" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity">
                                    
                                    <div @click.away="showLogoutModal = false" x-show="showLogoutModal" x-transition.scale.origin.bottom.duration.300ms class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 shadow-2xl flex flex-col items-center text-center cursor-default">
                                        
                                        {{-- Icon Merah --}}
                                        <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4 bg-rose-50 text-rose-600">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        </div>
                                        
                                        <h3 class="text-xl font-black text-gray-800 mb-2">Logout Sekarang?</h3>
                                        <p class="text-sm text-gray-500 mb-6">Anda akan keluar dari sesi saat ini.</p>

                                        <div class="flex gap-3 w-full">
                                            <button type="button" @click="showLogoutModal = false" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg text-sm transition-colors">
                                                Batal
                                            </button>
                                            
                                            {{-- Eksekusi Submit Form Pake Alpine --}}
                                            <button type="button" @click="document.getElementById('logout-form').submit()" class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-lg text-sm transition-colors">
                                                Ya, Logout
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>

                        </div>
                    </form>
                </div>
            </nav>

            <main class="flex-1 relative z-0">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
</html>