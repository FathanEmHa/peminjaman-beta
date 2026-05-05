<div>
    {{--
        approval-action-modal.blade.php
        Selalu di-mount, visibilitas dikontrol oleh $show.
        Mode yang mungkin: 'approve_confirm' | 'reject_form' | 'handover' | 'cancel_confirm'
    --}}

    @if($show && $loan)
        <div class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">

                {{-- ── Header Modal (warna berubah per mode) ──────────── --}}
                <div class="px-6 py-4 flex justify-between items-center
                    {{ in_array($mode, ['approve_confirm', 'handover']) ? 'bg-indigo-600' : '' }}
                    {{ $mode === 'reject_form'    ? 'bg-rose-600'   : '' }}
                    {{ $mode === 'cancel_confirm' ? 'bg-gray-700'   : '' }}">

                    <h3 class="text-white font-bold text-lg">
                        @if($mode === 'approve_confirm') Konfirmasi Persetujuan
                        @elseif($mode === 'reject_form') Tolak Peminjaman
                        @elseif($mode === 'handover')    Serah Terima Alat
                        @elseif($mode === 'cancel_confirm') Batalkan Peminjaman
                        @endif
                    </h3>
                    <button wire:click="closeModal" class="text-white/70 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- ── Body Modal ─────────────────────────────────────── --}}
                <div class="p-6">

                    {{-- Info Peminjam (semua mode) --}}
                    <div class="mb-5">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Peminjam</p>
                        <p class="font-bold text-gray-800 text-lg">{{ $loan->user->name }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">ID Transaksi: #{{ $loan->id }}</p>
                    </div>

                    {{-- ── MODE: approve_confirm ───────────────────────── --}}
                    @if($mode === 'approve_confirm')
                        <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-xl mb-4">
                            <p class="text-sm text-emerald-800 font-medium">Daftar alat yang akan disetujui:</p>
                            <ul class="mt-2 space-y-1">
                                @foreach($loan->items as $item)
                                    <li class="text-sm text-emerald-700 flex justify-between">
                                        <span>{{ $item->asset->name }}</span>
                                        <span class="font-bold">x{{ $item->quantity }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <p class="text-sm text-gray-600">Menyetujui akan memotong stok alat secara otomatis.</p>

                    {{-- ── MODE: reject_form ──────────────────────────── --}}
                    @elseif($mode === 'reject_form')
                        <div class="mb-4">
                            <label class="block text-[11px] font-medium text-gray-500 mb-1 uppercase tracking-wider">
                                Catatan Penolakan <span class="text-gray-400 normal-case">(opsional)</span>
                            </label>
                            <textarea
                                wire:model="rejectionNote"
                                rows="3"
                                placeholder="Contoh: Stok tidak mencukupi untuk periode tersebut."
                                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-rose-500 outline-none resize-none transition"
                            ></textarea>
                            @error('rejectionNote')
                                <span class="text-red-500 text-xs block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <p class="text-sm text-gray-600">Peminjaman akan ditandai sebagai <strong>Ditolak</strong> dan catatan di atas akan dicatat di activity log.</p>

                    {{-- ── MODE: handover ─────────────────────────────── --}}
                    @elseif($mode === 'handover')
                        <div class="mb-4">
                            <label class="block text-[11px] font-medium text-gray-500 mb-1 uppercase tracking-wider">
                                Bukti Foto Sebelum <span class="text-gray-400 normal-case">(opsional, disarankan)</span>
                            </label>
                            <input
                                type="file"
                                wire:model="photoBefore"
                                accept="image/*"
                                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 mb-1 focus:ring-2 focus:ring-indigo-500 outline-none file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                            >
                            @error('photoBefore')
                                <span class="text-red-500 text-xs block mb-2">{{ $message }}</span>
                            @enderror
                            <p class="text-xs text-gray-400 italic">*Foto muncul di halaman riwayat peminjam.</p>
                        </div>
                        <p class="text-sm text-gray-600 mt-3">Status akan berubah menjadi <strong>Ongoing</strong> setelah konfirmasi.</p>

                    {{-- ── MODE: cancel_confirm ────────────────────────── --}}
                    @elseif($mode === 'cancel_confirm')
                        <div class="p-4 bg-amber-50 border border-amber-100 rounded-xl mb-4">
                            <p class="text-sm text-amber-800">
                                Yakin ingin <strong>membatalkan</strong> peminjaman ini?
                                Stok alat berikut akan dikembalikan ke inventaris:
                            </p>
                            <ul class="mt-2 space-y-1">
                                @foreach($loan->items as $item)
                                    <li class="text-sm text-amber-700 flex justify-between">
                                        <span>{{ $item->asset->name }}</span>
                                        <span class="font-bold">+{{ $item->quantity }} stok kembali</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>

                {{-- ── Footer Tombol ───────────────────────────────────── --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex gap-3 justify-end">
                    <button wire:click="closeModal"
                        class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>

                    @if($mode === 'approve_confirm')
                        <button wire:click="approve" wire:loading.attr="disabled"
                            class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg transition-colors flex items-center gap-2 disabled:opacity-70">
                            <span wire:loading.remove wire:target="approve">Setujui Peminjaman</span>
                            <span wire:loading wire:target="approve">Memproses...</span>
                        </button>

                    @elseif($mode === 'reject_form')
                        <button wire:click="reject" wire:loading.attr="disabled"
                            class="px-6 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-lg transition-colors flex items-center gap-2 disabled:opacity-70">
                            <span wire:loading.remove wire:target="reject">Tolak Peminjaman</span>
                            <span wire:loading wire:target="reject">Memproses...</span>
                        </button>

                    @elseif($mode === 'handover')
                        <button wire:click="confirmHandover" wire:loading.attr="disabled"
                            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg transition-colors flex items-center gap-2 disabled:opacity-70">
                            <span wire:loading.remove wire:target="confirmHandover">Konfirmasi Penyerahan</span>
                            <span wire:loading wire:target="confirmHandover">Mengupload...</span>
                        </button>

                    @elseif($mode === 'cancel_confirm')
                        <button wire:click="cancel" wire:loading.attr="disabled"
                            class="px-6 py-2 bg-gray-700 hover:bg-gray-800 text-white text-sm font-bold rounded-lg transition-colors flex items-center gap-2 disabled:opacity-70">
                            <span wire:loading.remove wire:target="cancel">Ya, Batalkan</span>
                            <span wire:loading wire:target="cancel">Memproses...</span>
                        </button>
                    @endif
                </div>

            </div>
        </div>
    @endif
</div>
