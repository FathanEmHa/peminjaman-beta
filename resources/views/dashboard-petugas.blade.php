<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Petugas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Selamat datang, Petugas!
                    <div class="mt-6">
                        <span class="text-gray-500 italic border p-2 rounded">Modul Approval segera hadir...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>