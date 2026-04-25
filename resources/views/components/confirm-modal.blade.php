@props([
    'action', // Fungsi Livewire yang dipanggil (Contoh: 'store' atau 'delete(1)')
    'title' => 'Konfirmasi',
    'message' => 'Apakah Anda yakin ingin melanjutkan aksi ini?',
    'confirmText' => 'Ya, Lanjutkan',
    'cancelText' => 'Batal',
    'confirmColor' => 'blue' // Bisa diisi 'blue', 'red', atau 'emerald'
])

@php
    // Warna Dinamis untuk Tombol Konfirmasi
    $colorClasses = [
        'blue' => 'bg-blue-600 hover:bg-blue-700 text-white',
        'red' => 'bg-rose-600 hover:bg-rose-700 text-white',
        'emerald' => 'bg-emerald-600 hover:bg-emerald-700 text-white',
    ];
    $btnColor = $colorClasses[$confirmColor] ?? $colorClasses['blue'];
    
    // Warna Dinamis untuk Ikon
    $iconColors = [
        'blue' => 'bg-blue-50 text-blue-600',
        'red' => 'bg-rose-50 text-rose-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
    ];
    $iconColor = $iconColors[$confirmColor] ?? $iconColors['blue'];
@endphp

<div x-data="{ show: false }" class="inline-block w-full">
    
    {{-- Tombol Pemicu (Trigger) yang dibungkus dari luar --}}
    <div @click="show = true" class="w-full">
        {{ $trigger }}
    </div>

    {{-- Overlay Background Gelap --}}
    <div x-show="show" 
         style="display: none;" 
         class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity"
         x-transition.opacity.duration.300ms>
        
        {{-- Kotak Modal --}}
        <div @click.away="show = false" 
             x-show="show" 
             x-transition.scale.origin.bottom.duration.300ms 
             class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 shadow-2xl flex flex-col items-center text-center cursor-default">
            
            {{-- Ikon Peringatan / Info --}}
            <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4 {{ $iconColor }}">
                @if($confirmColor === 'red')
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                @else
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                @endif
            </div>
            
            <h3 class="text-xl font-black text-gray-800 mb-2">{{ $title }}</h3>
            <p class="text-sm text-gray-500 mb-6">{{ $message }}</p>

            <div class="flex gap-3 w-full">
                {{-- Tombol Batal (Menutup Modal) --}}
                <button type="button" @click="show = false" class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg text-sm transition-colors">
                    {{ $cancelText }}
                </button>
                
                {{-- Tombol Eksekusi Aksi Livewire --}}
                <button type="button" 
                        wire:click="{{ $action }}" 
                        class="flex-1 py-2.5 font-bold rounded-lg text-sm transition-colors flex justify-center items-center {{ $btnColor }}"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed">
                    <span wire:loading.remove wire:target="{{ $action }}">{{ $confirmText }}</span>
                    <span wire:loading wire:target="{{ $action }}">Memproses...</span>
                </button>
            </div>
        </div>
    </div>
</div>