<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Selamat datang, Admin!
                    <div class="mt-4">
                        <a href="{{ route('admin.categories') }}" wire:navigate class="bg-blue-500 text-white px-4 py-2 rounded">Kelola Kategori</a>
                        <a href="{{ route('admin.assets') }}" class="bg-green-500 text-white px-4 py-2 rounded ml-2">Kelola Alat</a>
                        <a href="{{ route('admin.users') }}" class="bg-indigo-600 text-white px-4 py-2 rounded ml-2 font-bold">Kelola Pengguna</a>
                        <a href="{{ route('admin.logs') }}" class="bg-gray-800 text-white px-4 py-2 rounded ml-2 font-bold">Log Aktivitas</a>
                        <a href="{{ route('admin.loans') }}" class="bg-yellow-600 text-white px-4 py-2 rounded ml-2 font-bold">Data Peminjaman</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>