@props([
    'name' => 'team',
    'strokeWidth' => '1.8',
])

{{--
    Komponen Ikon Vektor Konsol Meja Operasional (SEMAR PST - Meja Operator)
    ID: Komponen ikon berbasis vektor presisi tinggi untuk metrik operasional konsol meja.
    EN: High-precision scalable vector icon component for operational desk console metrics.
--}}
<svg
    {{ $attributes->merge(['class' => 'w-5 h-5 shrink-0']) }}
    fill="none"
    viewBox="0 0 24 24"
    stroke="currentColor"
    stroke-width="{{ $strokeWidth }}"
    stroke-linecap="round"
    stroke-linejoin="round"
    aria-hidden="true"
>
    @switch($name)
        @case('team')
        @case('dilayani')
        @case('users')
            {{-- 3-Person Team Vector (Image 2 Top Icon - Dilayani Hari Ini) --}}
            <circle cx="12" cy="7" r="3" />
            <path d="M6.5 21v-1.5a3.5 3.5 0 0 1 3.5-3.5h4a3.5 3.5 0 0 1 3.5 3.5V21" />
            <circle cx="5" cy="8.5" r="2.2" />
            <path d="M2 21v-1a2.8 2.8 0 0 1 2.8-2.8h1.2" />
            <circle cx="19" cy="8.5" r="2.2" />
            <path d="M18 17.2h1.2a2.8 2.8 0 0 1 2.8 2.8v1" />
            <line x1="2" y1="21" x2="22" y2="21" />
            @break

        @case('duration')
        @case('durasi')
        @case('clock')
            {{-- Clock Vector with Hands at 12 and 4:30 (Image 2 Second Icon - Rata-Rata Durasi) --}}
            <circle cx="12" cy="12" r="9" />
            <polyline points="12 6.5 12 12 15.5 14.5" />
            @break

        @case('satisfaction')
        @case('statisfy')
        @case('thumbs-up')
            {{-- Rounded Thumbs-Up Vector (Image 2 Third Icon - Indeks Statisfy) --}}
            <rect x="3" y="10.5" width="3.5" height="9" rx="1" />
            <path d="M6.5 11h3.5a1.5 1.5 0 0 0 1.4-1L12.8 4.2a1.2 1.2 0 0 1 2.2.8v3.5a1.2 1.2 0 0 0 1.2 1.2h3a2 2 0 0 1 2 2.2l-1.2 6.2a2 2 0 0 1-2 1.6H6.5" />
            @break

        @case('queue-stack')
        @case('sisa-antrean')
        @case('stack')
        @case('tray')
            {{-- 3-Tier Stepped Queue Stack Vector (Image 2 Fourth Icon - Sisa Antrean) --}}
            <path d="M8.5 4h7a1.5 1.5 0 0 1 1.5 1.5V8h2a1.5 1.5 0 0 1 1.5 1.5v3H21a1.5 1.5 0 0 1 1.5 1.5v5a2 2 0 0 1-2 2H3.5a2 2 0 0 1-2-2v-5A1.5 1.5 0 0 1 3 12.5h1.5v-3A1.5 1.5 0 0 1 6 8h2.5V5.5A1.5 1.5 0 0 1 8.5 4z" />
            <line x1="6" y1="8" x2="18" y2="8" />
            <line x1="3" y1="12.5" x2="21" y2="12.5" />
            @break

        @default
            <circle cx="12" cy="12" r="9" />
    @endswitch
</svg>
