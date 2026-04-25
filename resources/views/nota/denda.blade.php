<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Denda Transaksi #{{ $loan->id }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Sembunyikan tombol cetak saat kertas di-print beneran */
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-800 p-8 flex justify-center min-h-screen">

    <div class="bg-white w-full max-w-2xl p-8 rounded-lg shadow-lg print:shadow-none print:p-0">
        
        <div class="border-b-2 border-gray-800 pb-6 mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black tracking-tighter">SIPA</h1>
                <p class="text-sm text-gray-500 font-medium tracking-widest">Sistem Informasi Peminjaman Alat</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-gray-800">NOTA DENDA</h2>
                <p class="text-sm text-gray-500">ID Transaksi: <span class="font-bold text-gray-800">#{{ $loan->id }}</span></p>
                <p class="text-sm text-gray-500">Tgl Cetak: {{ now()->format('d M Y, H:i') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-8 text-sm">
            <div>
                <p class="text-gray-500 mb-1">Informasi Peminjam:</p>
                <p class="font-bold text-gray-800 text-base">{{ $loan->user->name ?? 'Anonim' }}</p>
            </div>
            <div class="text-right">
                <p class="text-gray-500 mb-1">Waktu Transaksi:</p>
                <p><span class="font-medium text-gray-600">Pinjam:</span> {{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y, H:i') }}</p>
                <p><span class="font-medium text-gray-600">Batas Kembali:</span> {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y, H:i') }}</p>
                {{-- TAMBAHAN: INFO DIKEMBALIKAN PADA --}}
                <p class="text-rose-700 mt-1 font-bold">
                    <span class="font-medium">Dikembalikan:</span> 
                    {{ \Carbon\Carbon::parse($loan->return->created_at)->format('d M Y, H:i') }}
                </p>
            </div>
        </div>

        @php
            $lateFee = $loan->return->late_fee ?? 0;
            $damageFee = $loan->return->damage_fee ?? 0;
            $totalFee = $lateFee + $damageFee;
            $notes = $loan->return->condition_notes ?? '-';
            $status = $loan->return->fine_status == 'paid' ? 'LUNAS' : 'BELUM LUNAS';
        @endphp

        <div class="mb-8 border border-gray-300 rounded-lg overflow-hidden">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-100 border-b border-gray-300 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider">Keterangan Denda</th>
                        <th class="px-4 py-3 font-bold uppercase tracking-wider text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-4">
                            <p class="font-bold text-gray-800">Denda Keterlambatan Pengembalian</p>
                        </td>
                        <td class="px-4 py-4 text-right font-medium text-gray-800">
                            Rp {{ number_format($lateFee, 0, ',', '.') }}
                        </td>
                    </tr>

                    <tr>
                        <td class="px-4 py-4">
                            <p class="font-bold text-gray-800">Denda Kerusakan / Kehilangan Alat</p>
                            @if($damageFee > 0)
                                <p class="text-xs text-gray-500 mt-1 italic">Catatan: {{ $notes }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-right font-medium text-gray-800">
                            Rp {{ number_format($damageFee, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-end border-t-2 border-gray-800 pt-6">
            <div>
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Status Pembayaran</p>
                <div class="inline-block px-4 py-1 {{ $status == 'LUNAS' ? 'border-2 border-green-600 text-green-700' : 'border-2 border-red-600 text-red-700' }} font-black tracking-widest rounded">
                    {{ $status }}
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider mb-1">Total Harus Dibayar</p>
                <p class="text-4xl font-black text-gray-900">Rp {{ number_format($totalFee, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="mt-16 grid grid-cols-2 gap-8 text-center text-sm">
            <div>
                <p class="text-gray-500 mb-16">Peminjam,</p>
                <p class="font-bold text-gray-800 border-b border-gray-400 inline-block px-4">{{ $loan->user->name ?? '____________________' }}</p>
            </div>
            <div>
                <p class="text-gray-500 mb-16">Petugas SIPA,</p>
                <p class="font-bold text-gray-800 border-b border-gray-400 inline-block px-4">____________________</p>
            </div>
        </div>

        <div class="mt-10 pt-6 border-t border-gray-200 flex justify-end gap-4 no-print">
            <button onclick="window.close()" class="px-6 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
                Tutup
            </button>
            <button onclick="window.print()" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Ulang
            </button>
        </div>

    </div>

    <script>
        // Otomatis buka dialog print saat halaman selesai dimuat
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500); // Delay 0.5 detik biar CSS Tailwind ke-render sempurna dulu
        });
    </script>
</body>
</html>