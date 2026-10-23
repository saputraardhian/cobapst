@props([
    'livewire' => null,
    'activeModule' => null,
])

@php
    $currentModule = $activeModule ?? (
        request()->is('admin/statisfy*') ? 'statisfy' :
        (request()->is('admin/konsol-meja*') ? 'konsol-meja' :
        (request()->is('admin/presensi*') ? 'pristi' :
        (request()->is('admin/elena*') ? 'elena' :
        (request()->is('admin/complaints*') ? 'pengaduan' : 'workspace'))))
    );
@endphp

<x-filament-panels::layout.base :livewire="$livewire">
    @push('styles')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;600;700;800&family=Merriweather:wght@700;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background-color: #F4F5FA;
                color: #0F172A;
            }
            .font-mono-numbers {
                font-family: 'JetBrains Mono', monospace;
            }
            .font-serif-bps {
                font-family: 'Merriweather', serif;
            }
        </style>
    @endpush

    <div class="min-h-screen flex flex-col justify-between bg-[#F4F5FA] text-slate-800 antialiased selection:bg-[#005288] selection:text-white font-sans overflow-x-hidden">
        {{-- 1. HEADER TERPADU SINGLE-ROW (SYNCHRONIZED BACKOFFICE NAVBAR) --}}
        <header class="w-full bg-white border-b border-slate-200/80 px-4 sm:px-6 h-14 sm:h-16 flex items-center justify-between gap-3 shadow-[0_1px_3px_rgba(0,0,0,0.02)] shrink-0 z-20 sticky top-0">
            {{-- Seksi Kiri: Identitas Instansi & Logo BPS --}}
            <div class="flex items-center gap-2.5 sm:gap-3 flex-shrink-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group" title="Kembali ke Portal Publik">
                    <div class="w-9 h-9 rounded-xl bg-[#005288] flex items-center justify-center text-white shadow-xs group-hover:bg-[#00416c] transition">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-1.5 leading-none">
                            <span class="font-extrabold text-slate-900 tracking-tight text-sm sm:text-base">SEMARPST</span>
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                SSO INTERNAL
                            </span>
                        </div>
                        <p class="text-[10px] text-slate-500 font-medium mt-0.5 leading-none">BPS Provinsi Jawa Tengah</p>
                    </div>
                </a>
            </div>

            {{-- Seksi Tengah: Trek Navigasi Modul (Snug Pill Sesuai Mockup) --}}
            <div class="hidden md:flex items-center justify-center">
                <nav class="inline-flex items-center bg-[#F1F5F9] p-1 rounded-full gap-1 shadow-2xs">
                    <a href="{{ url('/admin') }}" class="px-3.5 py-1.5 rounded-full text-xs transition {{ $currentModule === 'workspace' ? 'bg-[#005288] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-medium' }}">
                        Workspace
                    </a>
                    <a href="{{ url('/admin/konsol-meja') }}" class="px-3.5 py-1.5 rounded-full text-xs transition flex items-center gap-1.5 {{ $currentModule === 'konsol-meja' ? 'bg-[#005288] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-medium' }}">
                        <span>Konsol Meja</span>
                        @if($currentModule === 'konsol-meja')
                            <span class="inline-flex items-center px-1.5 py-0.2 rounded-full text-[9px] font-extrabold bg-[#F7941D] text-slate-950">Meja 2</span>
                        @endif
                    </a>
                    <a href="{{ url('/admin/presensi') }}" class="px-3.5 py-1.5 rounded-full text-xs transition {{ $currentModule === 'pristi' ? 'bg-[#005288] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-medium' }}">
                        PRISTI
                    </a>
                    <a href="{{ url('/admin/statisfy') }}" class="px-3.5 py-1.5 rounded-full text-xs transition {{ $currentModule === 'statisfy' ? 'bg-[#005288] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-medium' }}">
                        STATISFY
                    </a>
                    <a href="{{ url('/admin/elena-evaluation') }}" class="px-3.5 py-1.5 rounded-full text-xs transition {{ $currentModule === 'elena' ? 'bg-[#005288] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-medium' }}">
                        ELENA
                    </a>
                    <a href="{{ url('/admin/complaints') }}" class="px-3.5 py-1.5 rounded-full text-xs transition {{ $currentModule === 'pengaduan' ? 'bg-[#005288] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-slate-900 font-medium' }}">
                        Pengaduan
                    </a>
                </nav>
            </div>

            {{-- Seksi Kanan: Sesi Pegawai & Tombol Aksi --}}
            <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                @if($currentModule === 'konsol-meja')
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        TV 23" Sinkron
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Live SKD 2026 Sync
                    </span>
                @endif

                @php
                    $user = filament()->auth()->user();
                    $initials = $user ? strtoupper(substr($user->name, 0, 2)) : 'HW';
                    $displayName = $user ? $user->name : 'Hendra Wibowo, S.Si.';
                    $displaySub = $currentModule === 'konsol-meja' ? 'Meja 2: Konsultasi' : 'Statistisi Ahli Pertama';
                @endphp

                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-[#005288] text-white font-bold text-xs flex items-center justify-center shadow-xs shrink-0">
                        {{ $initials }}
                    </div>
                    <div class="text-left leading-tight hidden sm:block">
                        <div class="text-xs font-bold text-slate-800 truncate max-w-[150px]">{{ $displayName }}</div>
                        <div class="text-[10px] text-slate-500 font-medium">{{ $displaySub }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ filament()->getLogoutUrl() }}" class="inline">
                    @csrf
                    <button type="submit" class="w-8 h-8 rounded-full border border-slate-200 bg-white hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 text-xs font-bold text-slate-600 transition flex items-center justify-center shadow-2xs cursor-pointer" title="Keluar Sesi">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </button>
                </form>
            </div>
        </header>

        {{-- 2. KONTEN HALAMAN UTAMA --}}
        <main class="flex-1 w-full max-w-[1440px] mx-auto px-4 sm:px-6 py-5">
            {{ $slot }}
        </main>

        {{-- 3. INSTITUTIONAL FOOTER BACKOFFICE (HANYA MUNCUL DI LUAR WORKSPACE) --}}
        @if($currentModule !== 'workspace')
        <footer class="w-full border-t border-slate-200/80 bg-white py-3 px-4 sm:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 shrink-0 mt-8">
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-lg bg-[#005288] flex items-center justify-center text-[10px] text-white font-black shrink-0">
                    BPS
                </div>
                <div>
                    <span class="font-bold text-slate-700">PRISTELA Backoffice v2.0</span>
                    <span class="text-slate-300 mx-1.5">•</span>
                    <span class="text-slate-500">Badan Pusat Statistik Provinsi Jawa Tengah | Jl. Pahlawan No. 6, Simpang Lima Semarang</span>
                </div>
            </div>

            <div class="flex items-center gap-3.5 text-[11px] text-slate-500 font-medium">
                <a href="{{ url('/admin') }}" class="hover:text-[#005288] transition">Workspace Pegawai</a>
                <span class="text-slate-300">•</span>
                <a href="{{ url('/admin/elena-evaluation') }}" class="hover:text-[#005288] transition">Modul ELENA</a>
                <span class="text-slate-300">•</span>
                <a href="{{ url('/admin/presensi') }}" class="hover:text-[#005288] transition">Modul PRISTI</a>
                <span class="text-slate-300">•</span>
                <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">ISO 9001:2015 Compliant</span>
            </div>
        </footer>
        @endif
    </div>
</x-filament-panels::layout.base>
