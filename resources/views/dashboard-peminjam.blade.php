<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Peminjam') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Message Modern --}}
            <!-- @if (session()->has('message'))
                <div
                    class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium text-sm">{{ session('message') }}</span>
                </div>
            @endif -->

            {{-- Banner Selamat Datang (Style Senada dengan Admin) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border-l-4 border-indigo-500">
                <div
                    class="p-6 text-gray-900 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        {{-- Mengambil nama user yang sedang login secara dinamis --}}
                        <h3 class="text-2xl font-bold text-gray-800 mb-1">Selamat datang,
                            {{ Auth::user()->name ?? 'Siswa/Peminjam' }}! 👋
                        </h3>
                        <p class="text-gray-500 text-sm">Pantau status pengajuan dan kelola riwayat peminjaman alat Anda
                            di sini.</p>
                    </div>

                    {{-- Opsional: Tombol Shortcut (Bisa disesuaikan route-nya jika "Ajukan Peminjaman" ada di halaman
                    terpisah) --}}
                    {{--
                    <div class="shrink-0 mt-2 sm:mt-0">
                        <a href="{{ route('peminjam.loans.create') }}"
                            class="inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-200 transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Ajukan Peminjaman
                        </a>
                    </div>
                    --}}
                </div>
            </div>

            {{-- Pemanggilan Komponen Livewire --}}
            <div class="w-full">
                <livewire:peminjam.loan-history />
            </div>

        </div>
    </div>
</x-app-layout>