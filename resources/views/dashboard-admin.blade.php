<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            {{-- Logo / Ikon Dashboard --}}
            <div class="p-2.5 bg-gradient-to-br from-indigo-600 to-blue-600 rounded-xl shadow-lg shadow-indigo-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 5a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6zM14 5a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1h-4a1 1 0 01-1-1V5z">
                    </path>
                </svg>
            </div>

            {{-- Teks Header --}}
            <div>
                <h2
                    class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-600 tracking-tight">
                    {{ __('Dashboard Admin') }}
                </h2>
                <p class="text-sm font-medium text-gray-500 mt-0.5">Pusat Kendali Sistem Manajemen Aset</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl mb-8 border-l-4 border-indigo-500">
                <div class="p-6 text-gray-900 flex flex-col sm:flex-row items-start sm:items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-1">Selamat datang, Admin! 👋</h3>
                        <p class="text-gray-500 text-sm">Pilih menu di bawah ini untuk mulai mengelola sistem.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <a href="{{ route('admin.categories') }}" wire:navigate
                    class="group bg-white overflow-hidden shadow-sm sm:rounded-2xl hover:shadow-md hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                    <div class="p-6 flex items-start">
                        <div
                            class="p-3 bg-blue-50 text-blue-500 rounded-xl group-hover:bg-blue-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition-colors">
                                Kelola Kategori</h4>
                            <p class="text-sm text-gray-500 mt-1">Atur dan kelompokkan jenis barang.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.assets') }}"
                    class="group bg-white overflow-hidden shadow-sm sm:rounded-2xl hover:shadow-md hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                    <div class="p-6 flex items-start">
                        <div
                            class="p-3 bg-green-50 text-green-500 rounded-xl group-hover:bg-green-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-bold text-gray-800 group-hover:text-green-600 transition-colors">
                                Kelola Alat</h4>
                            <p class="text-sm text-gray-500 mt-1">Inventarisasi data alat dan aset.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.users') }}"
                    class="group bg-white overflow-hidden shadow-sm sm:rounded-2xl hover:shadow-md hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                    <div class="p-6 flex items-start">
                        <div
                            class="p-3 bg-indigo-50 text-indigo-500 rounded-xl group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-bold text-gray-800 group-hover:text-indigo-600 transition-colors">
                                Kelola Pengguna</h4>
                            <p class="text-sm text-gray-500 mt-1">Manajemen akun staf dan member.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.loans') }}"
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
                                Data Peminjaman</h4>
                            <p class="text-sm text-gray-500 mt-1">Pantau status alat yang dipinjam.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.returns') }}"
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
                            <p class="text-sm text-gray-500 mt-1">Cek histori alat yang sudah kembali.</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.logs') }}"
                    class="group bg-white overflow-hidden shadow-sm sm:rounded-2xl hover:shadow-md hover:-translate-y-1 transition-all duration-300 border border-gray-100">
                    <div class="p-6 flex items-start">
                        <div
                            class="p-3 bg-gray-100 text-gray-600 rounded-xl group-hover:bg-gray-800 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-bold text-gray-800 group-hover:text-gray-900 transition-colors">Log
                                Aktivitas</h4>
                            <p class="text-sm text-gray-500 mt-1">Rekam jejak seluruh aktivitas sistem.</p>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>