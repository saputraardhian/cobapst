@props([
    'livewire' => null,
])

<x-filament-panels::layout.base :livewire="$livewire">
    @push('styles')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Merriweather:ital,wght@0,300;0,400;0,700;1,300&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            /* Reset Filament inputs inside SSO container to prevent icon text collisions */
            .sso-input-container input {
                border: 0 !important;
                background-color: transparent !important;
                padding: 0 !important;
                box-shadow: none !important;
                outline: none !important;
                height: auto !important;
            }
            .sso-input-container input:focus {
                box-shadow: none !important;
                border: 0 !important;
                outline: none !important;
            }
        </style>
    @endpush

    <div class="min-h-screen flex flex-col justify-between bg-[#F8F9FA] text-slate-800 antialiased selection:bg-[#005288] selection:text-white font-sans overflow-x-hidden">
        {{-- TOP HEADER NAVBAR --}}
        <header class="w-full bg-white border-b border-slate-200/80 px-6 sm:px-10 h-14 sm:h-16 flex items-center justify-between shadow-[0_1px_3px_rgba(0,0,0,0.02)] shrink-0 z-10">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#005288] flex items-center justify-center text-white shadow-sm shrink-0">
                    <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 17l6-6 4 4 8-8"/>
                    </svg>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-black text-slate-900 tracking-tight text-base font-sans">PRISTELA</span>
                    <span class="text-xs text-slate-500 font-medium ml-1">BPS Provinsi Jawa Tengah</span>
                </div>
            </div>

            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-xs font-semibold text-slate-700 shadow-sm transition">
                <svg class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                </svg>
                <span>Portal Publik</span>
            </a>
        </header>

        {{-- MAIN VIEWPORT --}}
        <main class="flex-1 flex flex-col items-center justify-center px-4 py-3 sm:py-5">
            {{ $slot }}
        </main>

        {{-- INSTITUTIONAL FOOTER --}}
        <footer class="w-full border-t border-slate-200/80 bg-white/80 backdrop-blur-sm py-2.5 sm:py-3 px-6 sm:px-10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 shrink-0 z-10">
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded bg-[#005288] flex items-center justify-center text-[10px] text-white font-bold shrink-0">
                    BPS
                </div>
                <span class="font-semibold text-slate-700">BPS Provinsi Jawa Tengah</span>
                <span class="text-slate-300">|</span>
                <span class="text-slate-400 font-mono text-[11px]">PRISTELA SSO v3.4</span>
            </div>

            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-slate-50 border border-slate-200 text-slate-600 text-[11px] font-medium">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Jaringan Terenkripsi TLS 1.3
                </span>
            </div>

            <div class="flex items-center gap-4 text-xs text-slate-500">
                <a href="{{ route('home') }}#layanan" class="hover:text-slate-800 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    Panduan
                </a>
                <a href="{{ route('home') }}#pengaduan" class="hover:text-slate-800 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    SPBE & Privasi
                </a>
                <a href="mailto:ipds3300@bps.go.id" class="hover:text-slate-800 transition flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Helpdesk IPDS
                </a>
            </div>
        </footer>
    </div>
</x-filament-panels::layout.base>
