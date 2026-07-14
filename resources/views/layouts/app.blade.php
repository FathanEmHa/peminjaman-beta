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
    <body
        class="font-sans antialiased text-gray-900 bg-gray-100 flex h-screen overflow-hidden"
        x-data="{ sidebarOpen: false }"
        @keydown.escape.window="sidebarOpen = false"
        @resize.window="if (window.innerWidth >= 1024) sidebarOpen = false"
        x-on:livewire:navigated.window="sidebarOpen = false"
    >

        {{-- Mobile backdrop --}}
        <div
            x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-black/60 lg:hidden"
            style="display: none;"
            aria-hidden="true"
        ></div>

        @include('layouts.sidebar')

        <div
            class="flex-1 flex flex-col overflow-y-auto relative min-w-0 overflow-x-hidden"
            :class="{ 'overflow-hidden': sidebarOpen }"
        >

            {{-- Hapus 'sticky top-0' biar navbarnya ikut ke-scroll ke atas --}}
            <nav class="bg-white/80 backdrop-blur-md border-b border-gray-200 h-16 flex items-center justify-between gap-2 px-4 lg:px-8 shrink-0 z-0 shadow-sm min-w-0 overflow-hidden">

                <div class="flex items-center gap-2 lg:gap-3 min-w-0 flex-1 lg:flex-initial overflow-hidden text-gray-800">
                    <button
                        type="button"
                        @click="sidebarOpen = true"
                        class="lg:hidden shrink-0 inline-flex items-center justify-center p-2 -ml-1 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                        aria-label="Buka menu navigasi"
                    >
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    @isset($header)
                        <div class="min-w-0 flex-1 lg:flex-initial overflow-hidden [&_h2]:truncate [&_h2]:text-base [&_h2]:sm:text-xl [&_h2]:lg:text-2xl [&_p]:hidden [&_p]:lg:block [&_p]:truncate [&_.flex]:min-w-0 [&_.flex]:gap-2 [&_.flex]:lg:gap-3 [&_.flex.items-center.gap-4]:lg:gap-4 [&_.rounded-xl]:shrink-0 [&_.rounded-xl]:p-2 [&_.rounded-xl]:lg:p-2.5 [&_svg.h-6]:w-5 [&_svg.h-6]:h-5 [&_svg.h-6]:lg:w-6 [&_svg.h-6]:lg:h-6">
                            {{ $header }}
                        </div>
                    @endisset
                </div>

                <div class="flex items-center gap-2 lg:gap-6 shrink-0">
                    <div class="text-right min-w-0 hidden sm:block lg:min-w-0">
                        <div class="text-sm font-bold text-gray-900 truncate max-w-[5rem] md:max-w-[8rem] lg:max-w-none">{{ auth()->user()->name }}</div>
                        <div class="text-xs font-bold text-indigo-600 uppercase tracking-wider hidden lg:block">{{ auth()->user()->role }}</div>
                    </div>

                    <div
                        class="sm:hidden shrink-0 h-8 w-8 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold"
                        title="{{ auth()->user()->name }}"
                    >
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="m-0 shrink-0">
                        @csrf

                        {{-- Kita akalin pake Alpine x-data --}}
                        <div x-data="{ showLogoutModal: false }" class="inline-block">

                            {{-- Tombol Pemicu --}}
                            <button
                                type="button"
                                @click="showLogoutModal = true"
                                class="inline-flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 font-bold py-1.5 px-2.5 lg:px-4 rounded-md text-sm transition"
                                aria-label="Logout"
                            >
                                <span class="hidden lg:inline">Logout</span>
                                <svg class="w-5 h-5 lg:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
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

            <main class="flex-1 relative z-0 min-w-0 overflow-x-hidden">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
</html>
