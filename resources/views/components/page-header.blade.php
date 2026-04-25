@props([
    'title'          => 'Halaman',
    'description'    => '',
    'iconColorClass' => 'bg-indigo-600',  // bg-* Tailwind class untuk kotak ikon
])

{{--
    <x-page-header
        title="Kelola Data Peminjaman"
        description="Pantau status, setujui, dan rekam peminjaman alat"
        icon-color-class="bg-blue-600"
    >
        <x-slot name="icon">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
        </x-slot>
    </x-page-header>

    Slot "icon" berisi <path> saja. Komponen ini yang menyiapkan <svg>-nya.
--}}

<div class="flex items-center gap-3">

    {{-- Kotak Ikon --}}
    <div class="p-2.5 {{ $iconColorClass }} rounded-xl shadow-lg">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 text-white"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            {{ $icon }}
        </svg>
    </div>

    {{-- Teks --}}
    <div>
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
        @if($description)
            <p class="text-sm font-medium text-gray-500 mt-0.5">
                {{ $description }}
            </p>
        @endif
    </div>

</div>
