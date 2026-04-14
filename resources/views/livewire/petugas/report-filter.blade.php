<div>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-indigo-100 rounded-lg">
                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
            </div>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Cetak Laporan Peminjaman
            </h2>
        </div>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">Filter Data Laporan</h3>
                <p class="text-sm text-gray-500 mt-1">Pilih parameter di bawah ini untuk menyaring data sebelum dicetak.</p>
            </div>

            {{-- GANTI JADI FORM METHOD GET --}}
            <form action="{{ route('petugas.laporan.cetak') }}" method="GET" target="_blank" x-data="{ selectedPeriod: '' }">
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Filter Periode Waktu --}}
                <div class="col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Periode Waktu</label>
                    {{-- Tambahin x-model buat nyambungin ke Alpine --}}
                    <select name="period" x-model="selectedPeriod" class="block w-full py-2.5 px-3 border border-gray-200 bg-gray-50 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Semua Waktu</option>
                        <option value="today">Hari Ini</option>
                        <option value="this_week">Minggu Ini</option>
                        <option value="this_month">Bulan Ini</option>
                        <option value="custom">Pilih Tanggal Manual...</option>
                    </select>
                </div>

                {{-- Filter Status --}}
                <div class="col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Status Peminjaman</label>
                    <select name="status" class="block w-full py-2.5 px-3 border border-gray-200 bg-gray-50 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending (Menunggu)</option>
                        <option value="approved">Approved (Disetujui)</option>
                        <option value="ongoing">Ongoing (Sedang Dipinjam)</option>
                        <option value="returned">Returned (Dikembalikan)</option>
                        <option value="rejected">Rejected (Ditolak)</option>
                    </select>
                </div>

                {{-- Filter Alat --}}
                <div class="col-span-1">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Berdasarkan Alat</label>
                    <select name="asset_id" class="block w-full py-2.5 px-3 border border-gray-200 bg-gray-50 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Semua Alat</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}">{{ $asset->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- INPUT TANGGAL MANUAL (Hanya Muncul Jika 'custom' Dipilih) --}}
                <div x-show="selectedPeriod === 'custom'" x-transition class="col-span-1 md:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                    <div>
                        <label class="block text-sm font-bold text-indigo-900 mb-2">Dari Tanggal</label>
                        <input type="date" name="start_date" class="block w-full py-2 px-3 border border-indigo-200 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-indigo-900 mb-2">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="block w-full py-2 px-3 border border-indigo-200 bg-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

            </div>

            {{-- Tombol Generate --}}
            <div class="p-6 border-t border-gray-100 bg-gray-50 flex justify-end">
                <button type="submit" class="inline-flex justify-center items-center px-6 py-3 bg-gray-900 text-white text-sm font-bold rounded-lg hover:bg-gray-800 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Generate & Print Laporan
                </button>
            </div>
        </form>    
        </div>
    </div>
</div>