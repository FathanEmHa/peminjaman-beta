<div>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ $backRoute }}" wire:navigate.hover class="p-2 bg-white hover:bg-gray-50 border border-gray-200 rounded-lg transition text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                <div class="p-2 bg-blue-600 rounded-xl shadow-lg shadow-blue-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                        Detail Peminjaman #{{ $loan->id }}
                    </h2>
                    <p class="text-sm text-gray-500 font-medium">Peminjam: {{ $loan->user->name }}</p>
                </div>
            </div>

            <span class="px-4 py-1.5 rounded-full text-sm font-bold border uppercase tracking-wider
                {{ $loan->status === 'returned' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 
                  ($loan->status === 'overdue' ? 'bg-rose-100 text-rose-700 border-rose-200' : 'bg-blue-100 text-blue-700 border-blue-200') }}">
                {{ $loan->status === 'overdue' ? 'OVERDUE' : $loan->status }}
            </span>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        {{-- 1. SECTION ALAT (HORIZONTAL SCROLL) --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                Daftar Alat yang Dipinjam
            </h3>
            <div class="flex flex-row gap-4 overflow-x-auto pb-4 scrollbar-hide">
                @foreach($loan->items as $item)
                    <div class="flex-none w-64 bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-center justify-between shadow-sm">
                        <div class="truncate mr-2">
                            <p class="font-bold text-gray-800 truncate">{{ $item->asset->name }}</p>
                            <p class="text-xs text-gray-500 uppercase">{{ $item->asset->category->name ?? 'Alat' }}</p>
                        </div>
                        <div class="bg-blue-600 text-white px-3 py-1 rounded-lg font-black text-sm">
                            x{{ $item->quantity }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 2. GRID INFO WAKTU & FOTO (EQUAL HEIGHT) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
            
            {{-- Box Waktu --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <h3 class="text-base font-bold text-gray-800 mb-6 pb-2 border-b">Informasi Waktu & Log</h3>
                <div class="space-y-6 flex-1 flex flex-col justify-center">
                    <div class="flex items-start gap-4">
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase">Tanggal Pinjam</p>
                            <p class="font-bold text-gray-800 text-lg">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d F Y, H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="p-2 bg-amber-50 rounded-lg text-amber-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase">Batas Pengembalian</p>
                            <p class="font-bold text-gray-800 text-lg">{{ \Carbon\Carbon::parse($loan->return_date)->format('d F Y, H:i') }}</p>
                        </div>
                    </div>

                    @if($loan->status === 'returned' && $loan->return)
                        <div class="flex items-start gap-4 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                            <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div>
                                <p class="text-xs text-emerald-600 font-bold uppercase">Sudah Dikembalikan Pada</p>
                                <p class="font-black text-emerald-700 text-lg">{{ \Carbon\Carbon::parse($loan->return->created_at)->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Box Dokumentasi --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
                <h3 class="text-base font-bold text-gray-800 mb-6 pb-2 border-b">Dokumentasi Fisik</h3>
                <div class="grid grid-cols-2 gap-4 flex-1">
                    {{-- Foto Sebelum --}}
                    <div class="relative group h-full border-2 border-dashed border-gray-200 rounded-xl overflow-hidden flex flex-col">
                        <div class="bg-gray-100 py-1.5 text-center text-[10px] font-black uppercase text-gray-500 tracking-widest">Awal</div>
                        <div class="flex-1 flex items-center justify-center p-2">
                            @if($loan->photo_before)
                                <img src="{{ asset('storage/' . $loan->photo_before) }}" class="max-h-full w-auto object-contain cursor-pointer hover:scale-105 transition" wire:click="viewImage('{{ asset('storage/' . $loan->photo_before) }}', 'Kondisi Awal')">
                            @else
                                <span class="text-gray-300 text-xs">Tanpa Foto</span>
                            @endif
                        </div>
                    </div>
                    {{-- Foto Sesudah --}}
                    <div class="relative group h-full border-2 border-dashed border-gray-200 rounded-xl overflow-hidden flex flex-col">
                        <div class="bg-gray-100 py-1.5 text-center text-[10px] font-black uppercase text-gray-500 tracking-widest">Kembali</div>
                        <div class="flex-1 flex items-center justify-center p-2">
                            @if($loan->photo_after)
                                <img src="{{ asset('storage/' . $loan->photo_after) }}" class="max-h-full w-auto object-contain cursor-pointer hover:scale-105 transition" wire:click="viewImage('{{ asset('storage/' . $loan->photo_after) }}', 'Kondisi Akhir')">
                            @else
                                <span class="text-gray-300 text-xs text-center">Menunggu<br>Kembali</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. SECTION DENDA (FULL WIDTH) --}}
        @php
            $estLateFee = $loan->nominal_denda ?? 0; 
            $actualLateFee = $loan->return->late_fee ?? 0;
            $actualDamageFee = $loan->return->damage_fee ?? 0;
            $hasFine = ($loan->status == 'returned' && ($actualLateFee + $actualDamageFee) > 0) || (in_array($loan->status, ['ongoing', 'overdue']) && $estLateFee > 0);
            $totalFine = $loan->status == 'returned' ? ($actualLateFee + $actualDamageFee) : $estLateFee;
            $isPaid = ($loan->return->fine_status ?? '') == 'paid';
        @endphp

        @if($hasFine)
            <div class="bg-white rounded-2xl shadow-sm border border-rose-100 overflow-hidden">
                <div class="bg-rose-600 px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="font-bold text-lg">Tagihan Denda</h3>
                    @if($loan->status == 'returned')
                        <a href="{{ route('nota.denda', $loan->id) }}" target="_blank" class="bg-white text-rose-600 px-4 py-1.5 rounded-lg text-xs font-black uppercase tracking-widest hover:bg-rose-50 transition">
                            Cetak Nota
                        </a>
                    @endif
                </div>
                <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="flex-1 w-full space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 font-medium uppercase tracking-tighter">Denda Keterlambatan</span>
                            <span class="font-bold text-gray-800">Rp {{ number_format($loan->status == 'returned' ? $actualLateFee : $estLateFee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 font-medium uppercase tracking-tighter">Denda Kerusakan</span>
                            <span class="font-bold text-gray-800">Rp {{ number_format($actualDamageFee, 0, ',', '.') }}</span>
                        </div>
                        @if($loan->return && $loan->return->condition_notes)
                            <div class="text-xs italic text-gray-400 bg-gray-50 p-2 rounded-lg border">
                                Note: "{{ $loan->return->condition_notes }}"
                            </div>
                        @endif
                    </div>

                    <div class="w-full md:w-auto flex flex-col items-center md:items-end">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Total Harus Dibayar</p>
                        <p class="text-4xl font-black text-rose-600 leading-none py-1">Rp {{ number_format($totalFine, 0, ',', '.') }}</p>
                        <div class="mt-2">
                            @if($loan->status == 'returned')
                                <span class="px-4 py-1 rounded-full text-[10px] font-black tracking-widest border {{ $isPaid ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }}">
                                    {{ $isPaid ? 'LUNAS' : 'BELUM LUNAS' }}
                                </span>
                            @else
                                <span class="text-[10px] font-bold text-amber-500 italic uppercase">Estimasi - Hubungi Petugas</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- LIGHTBOX MODAL --}}
    @if($showImageModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/95 backdrop-blur-sm transition-opacity" wire:click.self="closeImageModal">
            <div class="relative max-w-5xl w-full mx-4 flex flex-col items-center">
                <div class="w-full flex justify-between items-center mb-4 text-white">
                    <h3 class="text-lg font-medium">{{ $activeImageTitle }}</h3>
                    <button wire:click="closeImageModal" class="p-2 bg-gray-800 hover:bg-gray-700 rounded-full">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <img src="{{ $activeImageUrl }}" class="max-h-[85vh] w-auto object-contain rounded-lg shadow-2xl border-4 border-white/10">
            </div>
        </div>
    @endif
</div>