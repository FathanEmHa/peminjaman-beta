<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Petugas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Banner Selamat Datang --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl mb-8 border-l-4 border-indigo-500">
                <div class="p-6 text-gray-900 flex flex-col sm:flex-row items-start sm:items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-1">Selamat datang,
                            {{ Auth::user()->name ?? 'Petugas' }}! 👋
                        </h3>
                        <p class="text-gray-500 text-sm">Pilih menu di bawah ini untuk mulai mengelola operasional
                            peminjaman alat.</p>
                    </div>
                </div>
            </div>

            {{-- Grid Menu Shortcut Petugas --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Menu Peminjaman --}}
                <a href="{{ route('petugas.loans') ?? '#' }}"
                    class="group bg-white overflow-hidden shadow-sm sm:rounded-2xl hover:shadow-md hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                    <div class="p-6 flex items-start">
                        <div
                            class="p-3 bg-yellow-50 text-yellow-500 rounded-xl group-hover:bg-yellow-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-bold text-gray-800 group-hover:text-yellow-600 transition-colors">
                                Kelola Peminjaman</h4>
                            <p class="text-sm text-gray-500 mt-1">Setujui pengajuan dan serahkan alat ke siswa.</p>
                        </div>
                    </div>
                </a>

                {{-- Menu Pengembalian --}}
                <a href="{{ route('petugas.returns') ?? '#' }}"
                    class="group bg-white overflow-hidden shadow-sm sm:rounded-2xl hover:shadow-md hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                    <div class="p-6 flex items-start">
                        <div
                            class="p-3 bg-teal-50 text-teal-500 rounded-xl group-hover:bg-teal-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-bold text-gray-800 group-hover:text-teal-600 transition-colors">Data
                                Pengembalian</h4>
                            <p class="text-sm text-gray-500 mt-1">Konfirmasi penerimaan alat yang dikembalikan.</p>
                        </div>
                    </div>
                </a>

                {{-- Menu Katalog Alat (Opsional) --}}
                <a href="{{ route('petugas.assets') ?? '#' }}"
                    class="group bg-white overflow-hidden shadow-sm sm:rounded-2xl hover:shadow-md hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                    <div class="p-6 flex items-start">
                        <div
                            class="p-3 bg-blue-50 text-blue-500 rounded-xl group-hover:bg-blue-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition-colors">
                                Katalog Alat</h4>
                            <p class="text-sm text-gray-500 mt-1">Cek sisa stok ketersediaan alat di inventaris.</p>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>