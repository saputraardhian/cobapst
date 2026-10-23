<style>
    /* WORKSPACE PEGAWAI BENTO UI - BULLETPROOF MULTI-COLUMN LAYOUT */
    .workspace-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.25rem;
    }
    @media (max-width: 1024px) {
        .workspace-grid-2 {
            grid-template-columns: 1fr;
        }
    }

    .workspace-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 0.75rem;
    }
    @media (max-width: 640px) {
        .workspace-grid-3 {
            grid-template-columns: 1fr;
        }
    }

    .workspace-topics-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.75rem;
    }
    @media (max-width: 640px) {
        .workspace-topics-grid {
            grid-template-columns: 1fr;
        }
    }

    .workspace-btn-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.5rem;
    }
    @media (max-width: 640px) {
        .workspace-btn-grid {
            grid-template-columns: 1fr;
        }
    }

    .workspace-card-surface {
        background-color: #ffffff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.03), 0 1px 2px -1px rgba(0, 0, 0, 0.03);
    }

    .btn-disposisi-primary {
        background-color: #005288 !important;
        color: #ffffff !important;
        border: 1px solid #005288 !important;
    }
    .btn-disposisi-primary:hover {
        background-color: #00416c !important;
    }

    .btn-disposisi-secondary {
        background-color: #ffffff !important;
        color: #334155 !important;
        border: 1px solid #e2e8f0 !important;
    }
    .btn-disposisi-secondary:hover {
        background-color: #f8fafc !important;
        color: #0f172a !important;
    }
</style>

<div class="space-y-4 antialiased text-slate-800">
    {{-- SUB-BAR HEADER: TOMBOL KEMBALI & BREADCRUMB (MINIMALIST & CLEAN, NO HEAVY BOX) --}}
    <div class="w-full flex items-center justify-between gap-3 text-xs mb-1">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-700 font-semibold transition shadow-2xs">
            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Kembali ke Portal Publik</span>
        </a>

        <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
            <span>Portal Publik BPS</span>
            <span class="text-slate-300 font-bold">&gt;</span>
            <span class="font-bold text-slate-800">Workspace Pegawai</span>
        </div>
    </div>

    {{-- ROW 1: STATUS TUGAS & TIKET AKTIF + EVALUASI KINERJA ELENA --}}
    <div class="workspace-grid-2">
        {{-- KARTU 1 (KIRI): SELAMAT BERTUGAS & TIKET ANTREAN AKTIF --}}
        <div class="workspace-card-surface p-5 sm:p-6 flex flex-col justify-between">
            <div>
                {{-- Badges Shift & Meja --}}
                <div class="flex items-center flex-wrap gap-2 mb-3">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        {{ $officer['active_shift'] }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        {{ $officer['active_counter'] }}
                    </span>
                </div>

                {{-- Sapaan Ramah Pegawai (Sans-Serif High Craft) --}}
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight leading-snug">
                    Selamat Bertugas, <span class="text-[#005999]">{{ $officer['greeting_name'] }}!</span>
                </h1>
                <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                    Pusat Layanan Statistik Terpadu (PST) siap melayani masyarakat dan stakeholder dengan integritas, kecepatan, dan data berkualitas.
                </p>

                {{-- Kotak Tiket Antrean Aktif (Card Inset) --}}
                @if(!empty($activeTicket) && !empty($activeTicket['ticket_number']))
                <div class="mt-4 p-3.5 sm:p-4 rounded-xl border border-slate-200/90 bg-[#F8FAFC] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-start gap-3 min-w-0">
                        {{-- Nomor Tiket Jumbo --}}
                        <div class="w-12 h-12 rounded-xl bg-[#F7941D] text-white flex flex-col items-center justify-center font-black font-mono-numbers text-base shrink-0 shadow-xs">
                            <span class="text-[8px] uppercase tracking-wider opacity-90 leading-none">Tiket</span>
                            <span class="leading-none mt-0.5">{{ $activeTicket['ticket_number'] }}</span>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-bold text-slate-900 text-xs truncate">{{ $activeTicket['visitor_name'] }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $activeTicket['channel'] }}
                                </span>
                            </div>
                            <div class="my-1">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Status: {{ $activeTicket['status'] }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-600 truncate max-w-sm">
                                Topik: {{ $activeTicket['topic'] }}
                            </p>
                        </div>
                    </div>

                    {{-- Timer Waktu Tunggu --}}
                    <div class="text-left sm:text-right shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 sm:border-l border-slate-200 sm:pl-4">
                        <div class="text-[10px] uppercase font-bold text-slate-400">Waktu Tunggu</div>
                        <div class="text-xs font-black font-mono-numbers text-emerald-600 mt-0.5">
                            {{ $activeTicket['waiting_time'] }}
                        </div>
                    </div>
                </div>
                @else
                <div class="mt-4 p-3.5 sm:p-4 rounded-xl border border-slate-200/90 bg-[#F8FAFC] flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center font-bold text-base shrink-0 shadow-2xs">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Belum Ada Antrean Hari Ini - Siap Melayani
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-600 mt-1 leading-snug">
                                Loket sedang siaga melayani. Tiket antrean baru dari anjungan Kios Lobi akan otomatis terhubung ke meja layanan ini.
                            </p>
                        </div>
                    </div>
                    <div class="text-left sm:text-right shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 sm:border-l border-slate-200 sm:pl-4">
                        <span class="text-[10px] uppercase font-bold text-slate-400">Status Loket</span>
                        <div class="text-xs font-black text-emerald-700 mt-0.5 font-mono-numbers">STANDBY AKTIF</div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Tombol Aksi Petugas --}}
            <div class="mt-4 pt-3 border-t border-slate-100">
                <div class="workspace-btn-grid">
                    <a 
                        href="{{ url('/admin/konsol-meja') }}"
                        class="py-2.5 px-3 rounded-xl bg-[#005288] hover:bg-[#00416c] text-white text-xs font-bold transition flex items-center justify-center gap-2 shadow-xs"
                    >
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                        </svg>
                        <span>Buka Konsol Meja 2</span>
                    </a>

                    @if(!empty($activeTicket) && !empty($activeTicket['ticket_number']))
                    <button 
                        type="button" 
                        wire:click="callActiveTicket"
                        class="py-2.5 px-3 rounded-xl border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-800 text-xs font-bold transition flex items-center justify-center gap-2 shadow-2xs cursor-pointer"
                    >
                        <svg class="w-4 h-4 text-[#F7941D] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.757 3.63 8.25 4.51 8.25H6.75z" />
                        </svg>
                        <span>Panggil {{ $activeTicket['ticket_number'] }} Sekarang</span>
                    </button>
                    @else
                    <button 
                        type="button" 
                        disabled
                        class="py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-400 text-xs font-semibold transition flex items-center justify-center gap-2 cursor-not-allowed opacity-80"
                    >
                        <svg class="w-4 h-4 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.757 3.63 8.25 4.51 8.25H6.75z" />
                        </svg>
                        <span>Belum Ada Antrean Masuk</span>
                    </button>
                    @endif
                </div>
                
                <button 
                    type="button" 
                    wire:click="viewScheduleModal"
                    class="w-full mt-2 py-2 px-3 rounded-xl border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition flex items-center justify-center gap-2 shadow-2xs cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                    </svg>
                    <span>Lihat Jadwal Shift</span>
                </button>
            </div>
        </div>

        {{-- KARTU 2 (KANAN): EVALUASI KINERJA ELENA --}}
        <div class="workspace-card-surface p-5 sm:p-6 flex flex-col justify-between">
            <div>
                {{-- Header Kartu ELENA --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-xs">
                            ★
                        </div>
                        <div>
                            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800">
                                Evaluasi Kinerja ELENA
                            </h2>
                            <p class="text-[10px] text-slate-400 font-medium">{{ $elenaPerformance['period'] }}</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        {{ $elenaPerformance['delta'] }}
                    </span>
                </div>

                {{-- Skor Utama & Predikat --}}
                <div class="flex items-end justify-between mt-3.5">
                    <div>
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Skor Keseluruhan Kinerja</div>
                        <div class="flex items-baseline gap-1 mt-0.5">
                            <span class="text-4xl font-extrabold font-mono-numbers text-slate-900 leading-none">
                                {{ number_format($elenaPerformance['overall_score'], 1) }}
                            </span>
                            <span class="text-xs font-bold text-slate-400">/ {{ $elenaPerformance['max_score'] }}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ $elenaPerformance['predicate'] }}
                        </span>
                        <div class="text-[11px] text-slate-500 font-medium mt-1">
                            {{ $elenaPerformance['rank'] }}
                        </div>
                    </div>
                </div>

                {{-- Metrik Gabungan 3 Pilar --}}
                <div class="mt-3.5 p-3 rounded-xl border border-slate-200/80 bg-[#FAFBFD]">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[10px] uppercase font-bold tracking-wider text-slate-500">
                            Metrik Gabungan 3 Pilar
                        </span>
                        <span class="text-[10px] font-bold text-emerald-700 flex items-center gap-1">
                            <span>✓</span> DI ATAS TARGET
                        </span>
                    </div>

                    <div class="workspace-grid-3">
                        <div class="bg-white p-2 rounded-lg border border-slate-100 text-center shadow-2xs">
                            <div class="text-[10px] text-slate-500 font-medium">{{ $elenaPerformance['pillars']['pst']['label'] }}</div>
                            <div class="text-base font-bold font-mono-numbers text-slate-900 mt-0.5">
                                {{ $elenaPerformance['pillars']['pst']['score'] }}
                            </div>
                        </div>
                        <div class="bg-white p-2 rounded-lg border border-slate-100 text-center shadow-2xs">
                            <div class="text-[10px] text-slate-500 font-medium">{{ $elenaPerformance['pillars']['skms']['label'] }}</div>
                            <div class="text-base font-bold font-mono-numbers text-slate-900 mt-0.5">
                                {{ $elenaPerformance['pillars']['skms']['score'] }}
                            </div>
                        </div>
                        <div class="bg-white p-2 rounded-lg border border-slate-100 text-center shadow-2xs">
                            <div class="text-[10px] text-slate-500 font-medium">{{ $elenaPerformance['pillars']['pristi']['label'] }}</div>
                            <div class="text-base font-bold font-mono-numbers text-slate-900 mt-0.5">
                                {{ $elenaPerformance['pillars']['pristi']['score'] }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sub-Metrik Progress Bars --}}
                <div class="space-y-2 mt-3.5">
                    @foreach($elenaPerformance['sub_metrics'] as $metric)
                        <div>
                            <div class="flex items-center justify-between text-[11px] mb-1">
                                <span class="font-medium text-slate-700">{{ $metric['label'] }}</span>
                                <span class="font-bold font-mono-numbers text-slate-900">{{ number_format($metric['score'], 1) }} / 100</span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="{{ $metric['color'] }} h-1.5 rounded-full" style="width: {{ $metric['score'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-500 pt-3 mt-4 border-t border-slate-100">
                <span>Target Triwulan: <strong class="font-mono-numbers text-slate-700">{{ number_format($elenaPerformance['target_score'], 1) }}</strong></span>
                <a href="{{ url('/admin/elena-evaluation') }}" class="font-bold text-[#005999] hover:underline flex items-center gap-1">
                    <span>Rincian Rapor SKP</span>
                    <span>-&gt;</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ROW 2: UMPAN BALIK PENGUNJUNG PST + KOMPOSISI LAYANAN KONSULTASI --}}
    <div class="workspace-grid-2">
        {{-- KARTU 3 (KIRI): UMPAN BALIK PENGUNJUNG PST (STATISFY) --}}
        <div class="workspace-card-surface p-5 sm:p-6 flex flex-col justify-between">
            <div>
                {{-- Header Umpan Balik --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800">
                                Umpan Balik Pengunjung PST
                            </h2>
                            <p class="text-[10px] text-slate-400 font-medium">Hasil survei kepuasan instan STATISFY</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        ★ {{ $visitorFeedback['rating'] }}
                    </span>
                </div>

                {{-- Tag Pujian Terbanyak --}}
                <div class="mt-3.5">
                    <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-2">
                        Tag Pujian Terbanyak Dikumpulkan
                    </div>
                    <div class="flex items-center flex-wrap gap-1.5">
                        @foreach($visitorFeedback['tags'] as $tag)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $tag['color'] }}">
                                {{ $tag['label'] }} <strong class="ml-1 opacity-90">{{ $tag['count'] }}x</strong>
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Kotak Kutipan Testimoni Riil --}}
                <div class="mt-3.5 p-3.5 rounded-xl border border-slate-200/80 bg-[#F8FAFC]">
                    <p class="text-xs italic text-slate-700 leading-relaxed">
                        &ldquo;{{ $visitorFeedback['quote']['text'] }}&rdquo;
                    </p>

                    <div class="flex items-center justify-between gap-2 mt-3 pt-2.5 border-t border-slate-200/60">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-[#005288] text-white font-bold text-[10px] flex items-center justify-center shrink-0">
                                RW
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-900">{{ $visitorFeedback['quote']['author'] }}</div>
                                <div class="text-[10px] text-slate-500 font-medium">{{ $visitorFeedback['quote']['role'] }}</div>
                            </div>
                        </div>
                        <div class="text-[10px] text-slate-400 font-mono-numbers shrink-0">
                            {{ $visitorFeedback['quote']['timestamp'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-500 pt-3 mt-4 border-t border-slate-100">
                <span>{{ $visitorFeedback['positive_ratio'] }}</span>
                <a href="{{ url('/admin/statisfy') }}" class="font-bold text-[#005999] hover:underline flex items-center gap-1">
                    <span>Lihat Semua Ulasan</span>
                    <span>-&gt;</span>
                </a>
            </div>
        </div>

        {{-- KARTU 4 (KANAN): KOMPOSISI LAYANAN KONSULTASI --}}
        <div class="workspace-card-surface p-5 sm:p-6 flex flex-col justify-between">
            <div>
                {{-- Header Komposisi --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-sky-50 border border-sky-200 text-sky-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800">
                                Komposisi Layanan Konsultasi
                            </h2>
                            <p class="text-[10px] text-slate-400 font-medium">Distribusi topik konsultasi yang ditangani ({{ $consultationComposition['total_sessions'] }} Sesi)</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                        {{ $consultationComposition['period'] }}
                    </span>
                </div>

                {{-- Stacked Progress Bar --}}
                <div class="mt-4">
                    <div class="w-full h-3 rounded-full overflow-hidden flex bg-slate-100 shadow-2xs">
                        <div class="bg-[#005288] h-full" style="width: 45%" title="Kemiskinan 45%"></div>
                        <div class="bg-[#0284C7] h-full" style="width: 30%" title="PDRB 30%"></div>
                        <div class="bg-[#F7941D] h-full" style="width: 15%" title="Inflasi 15%"></div>
                        <div class="bg-[#6CBE45] h-full" style="width: 10%" title="Metodologi 10%"></div>
                    </div>

                    {{-- Skala Persentase --}}
                    <div class="flex items-center justify-between text-[10px] font-mono-numbers text-slate-400 mt-1.5 px-0.5">
                        <span>0%</span>
                        <span>25%</span>
                        <span>50%</span>
                        <span>75%</span>
                        <span>100%</span>
                    </div>
                </div>

                {{-- Matriks Topik Konsultasi (2x2 Grid) --}}
                <div class="workspace-topics-grid mt-4">
                    @foreach($consultationComposition['topics'] as $topic)
                        <div class="p-3 rounded-xl border border-slate-200/70 bg-[#FAFBFD] flex flex-col justify-between min-h-[62px]">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full {{ $topic['color'] }}"></span>
                                    <span class="text-xs font-bold text-slate-800">{{ $topic['name'] }}</span>
                                </div>
                                <span class="text-xs font-bold font-mono-numbers text-slate-900">{{ $topic['percentage'] }}%</span>
                            </div>
                            <div class="text-[10px] text-slate-500 mt-1">
                                <strong class="text-slate-700 font-mono-numbers">{{ $topic['sessions'] }} sesi</strong> • {{ $topic['desc'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-500 pt-3 mt-4 border-t border-slate-100">
                <span>Spesialisasi Utama: <strong class="text-slate-700">{{ $consultationComposition['specialization'] }}</strong></span>
                <button 
                    type="button" 
                    wire:click="exportConsultationCsv" 
                    class="font-bold text-[#005999] hover:underline flex items-center gap-1 cursor-pointer"
                >
                    <span>Unduh Rekap CSV</span>
                    <span>-&gt;</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ROW 3: JADWAL TUGAS JAGA + NOTIFIKASI & DISPOSISI --}}
    <div class="workspace-grid-2">
        {{-- KARTU 5 (KIRI): JADWAL TUGAS JAGA 7 HARI KE DEPAN (PRISTI) --}}
        <div class="workspace-card-surface p-5 sm:p-6 flex flex-col justify-between">
            <div>
                {{-- Header Jadwal --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-blue-50 border border-blue-200 text-[#005288] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-[#005288]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800">
                                Jadwal Tugas Jaga 7 Hari Ke Depan
                            </h2>
                            <p class="text-[10px] text-slate-400 font-medium">Penugasan loket PST dan Tim Pengolahan Data</p>
                        </div>
                    </div>
                    <button 
                        type="button" 
                        wire:click="exportCalendar"
                        class="px-2.5 py-1 rounded-lg border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition flex items-center gap-1.5 shadow-2xs cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        <span>Ekspor Kalender</span>
                    </button>
                </div>

                {{-- Daftar 5 Baris Jadwal --}}
                <div class="space-y-2 mt-3.5">
                    @foreach($schedules as $sched)
                        <div class="p-2.5 rounded-xl border {{ $sched['is_today'] ? 'border-blue-200 bg-blue-50/30' : 'border-slate-100 bg-[#FAFBFD]' }} flex items-center justify-between gap-3 transition">
                            <div class="flex items-center gap-3 min-w-0">
                                {{-- Kotak Tanggal & Hari --}}
                                <div class="w-10 h-10 rounded-lg {{ $sched['is_today'] ? 'bg-[#005288] text-white' : 'bg-slate-100 border border-slate-200 text-slate-700' }} flex flex-col items-center justify-center leading-none shrink-0 shadow-2xs">
                                    <span class="text-[8px] font-bold uppercase tracking-wider opacity-90">{{ $sched['day'] }}</span>
                                    <span class="text-sm font-black font-mono-numbers mt-0.5">{{ $sched['date'] }}</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="text-xs font-bold text-slate-900 truncate">{{ $sched['counter'] }}</span>
                                        @if(!empty($sched['badge']))
                                            <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                {{ $sched['badge'] }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-[10px] text-slate-500 font-medium mt-0.5 truncate">
                                        {{ $sched['time'] }} • {{ $sched['partner'] }}
                                    </div>
                                </div>
                            </div>

                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold shrink-0 {{ $sched['is_today'] ? 'bg-[#005288] text-white' : 'bg-slate-100 text-slate-600' }}">
                                {{ $sched['tag'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-500 pt-3 mt-4 border-t border-slate-100">
                <span>Kebutuhan tukar shift? Ajukan melalui menu PRISTI</span>
                <button 
                    type="button" 
                    wire:click="requestShiftTransfer"
                    class="font-bold text-[#005999] hover:underline cursor-pointer flex items-center gap-1"
                >
                    <span>Ajukan Pengalihan Tugas</span>
                    <span>-&gt;</span>
                </button>
            </div>
        </div>

        {{-- KARTU 6 (KANAN): NOTIFIKASI & DISPOSISI --}}
        <div class="workspace-card-surface p-5 sm:p-6 flex flex-col justify-between">
            <div>
                {{-- Header Notifikasi --}}
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800">
                                Notifikasi & Disposisi
                            </h2>
                            <p class="text-[10px] text-slate-400 font-medium">{{ $unreadNotificationsCount }} pesan memerlukan tindak kerja Anda</p>
                        </div>
                    </div>
                    @if($unreadNotificationsCount > 0)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                            <span>{{ $unreadNotificationsCount }} Baru</span>
                        </span>
                    @endif
                </div>

                {{-- Daftar Notifikasi --}}
                <div class="space-y-2 mt-3.5">
                    {{-- Item 1: Disposisi Prioritas (Refined Inset) --}}
                    <div class="p-3.5 rounded-xl border border-amber-200/80 border-l-4 border-l-[#F7941D] bg-amber-50/25">
                        <div class="flex items-center justify-between pb-1.5 mb-1.5 border-b border-amber-200/50">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[9px] font-bold bg-amber-100/90 text-amber-900 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#F7941D]"></span>
                                <span>Disposisi Prioritas</span>
                            </span>
                            <span class="text-[10px] font-bold font-mono-numbers text-amber-800">
                                Batas: 2 Jam Lagi
                            </span>
                        </div>
                        <h3 class="text-xs font-bold text-slate-900 leading-tight">
                            Tiket #PST-2026-042: Permintaan Klarifikasi Data Publikasi Makro Jateng
                        </h3>
                        <p class="text-[11px] text-slate-600 mt-1 leading-normal">
                            Disposisi dari Kepala BPS Provinsi Jateng untuk verifikasi data ketimpangan/pengeluaran BPS Rilis 2026.
                        </p>

                        <div class="flex items-center gap-2 mt-2.5">
                            <button 
                                type="button" 
                                wire:click="respondDisposition('PST-2026-042')"
                                class="btn-disposisi-primary px-3.5 py-1.5 rounded-lg text-white text-[11px] font-bold transition shadow-2xs cursor-pointer flex items-center gap-1.5"
                            >
                                <svg class="w-3.5 h-3.5 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                </svg>
                                <span>Tanggapi Disposisi</span>
                            </button>
                            <button 
                                type="button" 
                                wire:click="approveFile('pristi-01')"
                                class="btn-disposisi-secondary px-3.5 py-1.5 rounded-lg text-slate-700 text-[11px] font-semibold transition shadow-2xs cursor-pointer flex items-center gap-1.5"
                            >
                                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span>Setujui Berkas</span>
                            </button>
                        </div>
                    </div>

                    {{-- Item 2: Pengumuman Manajemen --}}
                    <div class="p-2.5 rounded-xl border border-slate-200/70 bg-[#FAFBFD] flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                Pengumuman Manajemen
                            </span>
                            <p class="text-[11px] text-slate-600 truncate pl-3 mt-0.5">
                                Briefing Peningkatan Layanan WBBM BPS Jateng pukul 16:00 WIB di Ruang Rapat Pahlawan.
                            </p>
                        </div>
                        <span class="text-[10px] text-slate-400 font-mono-numbers shrink-0">10:00 WIB</span>
                    </div>

                    {{-- Item 3: Approval Pristi Berhasil --}}
                    <div class="p-2.5 rounded-xl border border-slate-200/70 bg-[#FAFBFD] flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Approval PRISTI Berhasil
                            </span>
                            <p class="text-[11px] text-slate-600 truncate pl-3 mt-0.5">
                                Dispensasi Dinas Luar (Workshop Satu Data Semarang) telah disetujui Kasubbag Umum.
                            </p>
                        </div>
                        <span class="text-[10px] text-slate-400 font-mono-numbers shrink-0">08:12 WIB</span>
                    </div>

                    {{-- Item 4: Evaluasi Kinerja ELENA --}}
                    <div class="p-2.5 rounded-xl border border-slate-200/70 bg-[#FAFBFD] flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                Evaluasi Kinerja ELENA
                            </span>
                            <p class="text-[11px] text-slate-600 truncate pl-3 mt-0.5">
                                Penilaian SKP dan Evaluasi Perilaku Kerja Semester I telah difinalisasi Penilai Kinerja.
                            </p>
                        </div>
                        <span class="text-[10px] text-slate-400 font-mono-numbers shrink-0">Kemarin</span>
                    </div>

                    {{-- Item 5: Survei Statisfy Baru --}}
                    <div class="p-2.5 rounded-xl border border-slate-200/70 bg-[#FAFBFD] flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Survei Statisfy Baru
                            </span>
                            <p class="text-[11px] text-slate-600 truncate pl-3 mt-0.5">
                                Tamu Meja 2 memberikan rating bintang 5 dengan testimoni kepuasan layanan konsultasi.
                            </p>
                        </div>
                        <span class="text-[10px] text-slate-400 font-mono-numbers shrink-0">Kemarin</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-500 pt-3 mt-4 border-t border-slate-100">
                <span>Pusat Bantuan &amp; Admin Teknis</span>
                <button 
                    type="button" 
                    wire:click="markAllAsRead" 
                    class="font-bold text-[#005999] hover:underline cursor-pointer flex items-center gap-1"
                >
                    <span>Tandai Semua Dibaca</span>
                    <span>-&gt;</span>
                </button>
            </div>
        </div>
    </div>

    {{-- FOOTER BAR: STATUS KONEKSI SERVER BPS (SATU-SATUNYA FOOTER PADA HALAMAN INI) --}}
    <div class="w-full flex flex-col sm:flex-row sm:items-center justify-between gap-2 px-4 py-2.5 bg-white rounded-xl border border-slate-200/80 text-[11px] text-slate-500 shadow-2xs">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="font-bold text-slate-700">{{ $serverStatus['status'] }}</span>
            <span class="text-slate-300">•</span>
            <span>Sinkronisasi Terakhir: <strong class="font-mono-numbers text-slate-700">{{ $serverStatus['last_sync'] }}</strong></span>
            <span class="text-slate-300">•</span>
            <span>Latensi: <strong class="font-mono-numbers text-emerald-700">{{ $serverStatus['latency'] }}</strong></span>
        </div>

        <div class="flex items-center gap-2 text-slate-400">
            <span>{{ $serverStatus['system_version'] }}</span>
            <span class="text-slate-300">•</span>
            <span>{{ $serverStatus['institution'] }}</span>
            <span class="text-slate-300">•</span>
            <span class="font-semibold text-slate-600">{{ $serverStatus['tagline'] }}</span>
        </div>
    </div>
</div>
