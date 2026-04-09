<aside class="w-64 bg-gray-900 text-white flex flex-col h-screen overflow-y-auto shrink-0 shadow-lg">
    <div
        class="h-16 flex items-center justify-center border-b border-gray-800 font-bold text-xl tracking-wider shrink-0">
        PINJAM<span class="text-indigo-500">ALAT</span>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 text-sm font-medium">

        <a href="{{ route('dashboard') }}" wire:navigate.hover
            class="block px-4 py-2 rounded-md hover:bg-gray-800 hover:text-indigo-400 transition {{ request()->routeIs('*.dashboard') ? 'bg-gray-800 text-indigo-400' : 'text-gray-300' }}">
            Dashboard
        </a>

        @if(auth()->user()->role === 'admin')
            <div class="pt-4 pb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">Master Data</div>
            <a href="{{ route('admin.categories') }}" wire:navigate.hover
                class="block px-4 py-2 rounded-md hover:bg-gray-800 hover:text-indigo-400 transition {{ request()->routeIs('admin.categories') ? 'bg-gray-800 text-indigo-400' : 'text-gray-300' }}">Kategori</a>
            <a href="{{ route('admin.assets') }}" wire:navigate.hover
                class="block px-4 py-2 rounded-md hover:bg-gray-800 hover:text-indigo-400 transition {{ request()->routeIs('admin.assets') ? 'bg-gray-800 text-indigo-400' : 'text-gray-300' }}">Alat
                (Assets)</a>
            <a href="{{ route('admin.users') }}" wire:navigate.hover
                class="block px-4 py-2 rounded-md hover:bg-gray-800 hover:text-indigo-400 transition {{ request()->routeIs('admin.users') ? 'bg-gray-800 text-indigo-400' : 'text-gray-300' }}">Pengguna</a>

            <div class="pt-4 pb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">Monitoring</div>
            <a href="{{ route('admin.loans') }}" wire:navigate.hover
                class="block px-4 py-2 rounded-md hover:bg-gray-800 hover:text-indigo-400 transition {{ request()->routeIs('admin.loans') ? 'bg-gray-800 text-indigo-400' : 'text-gray-300' }}">Semua
                Peminjaman</a>
            <a href="{{ route('admin.returns') }}" wire:navigate.hover
                class="block px-4 py-2 rounded-md hover:bg-gray-800 hover:text-indigo-400 transition {{ request()->routeIs('admin.returns') ? 'bg-gray-800 text-indigo-400' : 'text-gray-300' }}">Semua
                Pengembalian</a>
            <a href="{{ route('admin.logs') }}" wire:navigate.hover
                class="block px-4 py-2 rounded-md hover:bg-gray-800 hover:text-indigo-400 transition {{ request()->routeIs('admin.logs') ? 'bg-gray-800 text-indigo-400' : 'text-gray-300' }}">Log
                Aktivitas</a>

        @endif

        @if(auth()->user()->role === 'petugas')
            <a href="{{ route('petugas.katalog') }}" target="_blank" wire:navigate.hover
                class="block px-4 py-2 rounded-md hover:bg-gray-800 hover:text-indigo-400 transition text-gray-300">Katalog
                Assets</a>
        @endif

        @if(auth()->user()->role === 'peminjam')
            <a href="{{ route('peminjam.katalog') }}" wire:navigate.hover
                class="block px-4 py-2 rounded-md hover:bg-gray-800 hover:text-indigo-400 transition {{ request()->routeIs('peminjam.katalog') ? 'bg-gray-800 text-indigo-400' : 'text-gray-300' }}">Katalog Alat</a>
            <div class="pt-4 pb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">Transaksi</div>
            <a href="{{ route('peminjam.loans.create') }}" wire:navigate.hover
                class="block px-4 py-2 rounded-md hover:bg-gray-800 hover:text-indigo-400 transition {{ request()->routeIs('peminjam.loans.create') ? 'bg-gray-800 text-indigo-400' : 'text-gray-300' }}">Ajukan
                Peminjaman</a>
            <a href="{{ route('peminjam.loans.history') }}" wire:navigate.hover
                class="block px-4 py-2 rounded-md hover:bg-gray-800 hover:text-indigo-400 transition {{ request()->routeIs('peminjam.loans.history') ? 'bg-gray-800 text-indigo-400' : 'text-gray-300' }}">Riwayat
                Peminjaman</a>
        @endif

    </nav>
</aside>