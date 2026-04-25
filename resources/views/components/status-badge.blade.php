@props([
    'status' => 'pending',
    'size'   => 'md', // 'sm' | 'md'
])

{{--
    Penggunaan:
        <x-status-badge :status="$loan->status" />
        <x-status-badge :status="$loan->status" size="sm" />

    Props:
        status — string status loan: pending | approved | ongoing | overdue
                                     returned | rejected | cancelled
                                     awaiting_return | returning
        size   — 'sm' (text-[9px]) | 'md' (text-[10px]) — default md
--}}

@php
    // ── Map status → Tailwind classes ──────────────────────────────────
    $map = [
        'pending'         => ['classes' => 'bg-yellow-100 text-yellow-700 border-yellow-200',  'label' => 'Pending'],
        'approved'        => ['classes' => 'bg-blue-100   text-blue-700   border-blue-200',    'label' => 'Approved'],
        'ongoing'         => ['classes' => 'bg-indigo-100 text-indigo-700 border-indigo-200',  'label' => 'Ongoing'],
        'overdue'         => ['classes' => 'bg-rose-100   text-rose-700   border-rose-200',    'label' => 'Overdue'],
        'returned'        => ['classes' => 'bg-emerald-100 text-emerald-700 border-emerald-200','label' => 'Returned'],
        'rejected'        => ['classes' => 'bg-red-100    text-red-700    border-red-200',     'label' => 'Rejected'],
        'cancelled'       => ['classes' => 'bg-gray-100   text-gray-600   border-gray-200',    'label' => 'Cancelled'],
        'awaiting_return' => ['classes' => 'bg-orange-100 text-orange-700 border-orange-200',  'label' => 'Await Return'],
        'returning'       => ['classes' => 'bg-teal-100   text-teal-700   border-teal-200',    'label' => 'Returning'],
    ];

    $resolved     = $map[$status] ?? ['classes' => 'bg-gray-100 text-gray-600 border-gray-200', 'label' => strtoupper($status)];
    $badgeClasses = $resolved['classes'];
    $label        = $resolved['label'];

    $textSize = $size === 'sm' ? 'text-[9px]' : 'text-[10px]';
@endphp

<span class="inline-flex items-center px-2.5 py-1 rounded-full font-bold border uppercase tracking-wider {{ $textSize }} {{ $badgeClasses }}">
    {{ $label }}
</span>
