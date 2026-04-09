<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Alat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-black p-8 font-sans" onload="window.print()">
    <div class="text-center mb-8 border-b-2 border-black pb-4">
        <h2 class="text-2xl font-bold uppercase">Laporan Peminjaman Alat</h2>
        <p class="text-gray-700 mt-1">Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table class="w-full border-collapse border border-black text-sm">
        <thead>
            <tr class="bg-gray-200">
                <th class="border border-black px-4 py-2 text-left font-bold">ID</th>
                <th class="border border-black px-4 py-2 text-left font-bold">Peminjam</th>
                <th class="border border-black px-4 py-2 text-left font-bold">Daftar Alat</th>
                <th class="border border-black px-4 py-2 text-left font-bold">Tgl Pinjam</th>
                <th class="border border-black px-4 py-2 text-left font-bold">Tgl Kembali</th>
                <th class="border border-black px-4 py-2 text-center font-bold">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $loan)
            <tr>
                <td class="border border-black px-4 py-2">#{{ $loan->id }}</td>
                <td class="border border-black px-4 py-2 font-medium">{{ $loan->user->name }}</td>
                <td class="border border-black px-4 py-2">
                    <ul class="list-disc ml-4">
                        @foreach($loan->items as $item)
                            <li>{{ $item->asset->name }} ({{ $item->quantity }})</li>
                        @endforeach
                    </ul>
                </td>
                <td class="border border-black px-4 py-2">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                <td class="border border-black px-4 py-2">{{ \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') }}</td>
                <td class="border border-black px-4 py-2 text-center uppercase font-bold text-xs">{{ $loan->status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="border border-black px-4 py-6 text-center italic text-gray-600">Belum ada data peminjaman yang sesuai dengan filter.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-12 flex justify-end">
        <div class="text-center">
            <p class="mb-16">Mengetahui, Petugas</p>
            <p class="font-bold underline">{{ auth()->user()->name }}</p>
        </div>
    </div>
</body>
</html>