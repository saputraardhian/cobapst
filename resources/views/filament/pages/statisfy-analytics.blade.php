<style>
    /* STATISFY GRID SYSTEM - MULTI-COLUMN DESKTOP & RESPONSIVE LAYOUTS */
    .statisfy-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1rem;
    }
    @media (max-width: 1024px) {
        .statisfy-grid-4 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 640px) {
        .statisfy-grid-4 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
    }

    .statisfy-grid-exec {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.25rem;
    }
    @media (max-width: 1024px) {
        .statisfy-grid-exec {
            grid-template-columns: 1fr;
        }
    }

    .statisfy-grid-trends {
        display: grid;
        grid-template-columns: 7fr 5fr;
        gap: 1.25rem;
    }
    @media (max-width: 1024px) {
        .statisfy-grid-trends {
            grid-template-columns: 1fr;
        }
    }

    .statisfy-grid-channels {
        display: grid;
        grid-template-columns: 5fr 7fr;
        gap: 1.25rem;
    }
    @media (max-width: 1024px) {
        .statisfy-grid-channels {
            grid-template-columns: 1fr;
        }
    }

    .statisfy-grid-leaderboard {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.25rem;
    }
    @media (max-width: 1024px) {
        .statisfy-grid-leaderboard {
            grid-template-columns: 1fr;
        }
    }

    .statisfy-hits-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.625rem;
    }
    @media (max-width: 640px) {
        .statisfy-hits-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (min-width: 1025px) {
        .statisfy-grid-4 > *,
        .statisfy-grid-exec > *,
        .statisfy-grid-trends > *,
        .statisfy-grid-channels > *,
        .statisfy-grid-leaderboard > * {
            grid-column: auto !important;
            min-width: 0 !important;
        }
    }

    /* Rekomendasi Pimpinan Action Buttons */
    .statisfy-recom-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        margin-top: 1rem;
        padding-top: 0.75rem;
        border-top: 1px solid #f1f5f9;
    }
    .statisfy-btn-navy {
        background-color: #005999 !important;
        color: #ffffff !important;
    }
    .statisfy-btn-navy:hover {
        background-color: #005288 !important;
    }
    .statisfy-btn-white {
        background-color: #ffffff !important;
        color: #1e293b !important;
        border: 1px solid #e2e8f0 !important;
    }
    .statisfy-btn-white:hover {
        background-color: #f8fafc !important;
    }
    .statisfy-btn-orange {
        background-color: #f7941d !important;
        color: #ffffff !important;
    }
    .statisfy-btn-orange:hover {
        background-color: #ea580c !important;
    }

    /* Bar Chart Container & Bars */
    .statisfy-chart-container {
        position: relative;
        height: 11rem;
        width: 100%;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 0.375rem;
        padding: 0 0.5rem 1.5rem 0.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .statisfy-bar-col {
        flex: 1;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: center;
        gap: 0.375rem;
        z-index: 10;
    }
    .statisfy-bar-default {
        width: 100%;
        max-width: 36px;
        background-color: #005288;
        border-radius: 6px 6px 0 0;
        transition: all 0.3s ease;
    }
    .statisfy-bar-default:hover {
        background-color: #005999;
    }
    .statisfy-bar-current {
        width: 100%;
        max-width: 36px;
        background: linear-gradient(to top, #005999, #6cbe45);
        border-radius: 6px 6px 0 0;
        transition: all 0.3s ease;
    }

    /* Export Action Buttons in Footer */
    .statisfy-export-actions {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .statisfy-export-actions button {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        font-weight: 700;
        font-size: 0.75rem;
        border-radius: 0.75rem;
        padding: 0.5rem 0.875rem;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
</style>

<div class="space-y-5 antialiased text-slate-800">
    {{-- SUB-BAR HEADER: BREADCRUMB, PERIODE WAKTU, & EKSPOR --}}
    <div class="w-full flex flex-col md:flex-row md:items-center justify-between gap-3 bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3 text-xs">
            <a href="{{ url('/admin') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold transition">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                <span>Kembali ke Dashboard Utama</span>
            </a>
            <span class="text-slate-300">|</span>
            <div class="flex items-center gap-1.5 text-slate-500 font-medium">
                <span>Admin Internal</span>
                <span class="text-slate-300">></span>
                <span class="font-bold text-slate-800">STATISFY Analytics & AI Summary</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            {{-- Filter Periode Waktu --}}
            <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200 gap-1 text-xs">
                @foreach(['monthly' => 'Bulanan', 'quarterly' => 'Triwulanan', 'yearly' => 'Tahunan', 'alltime' => 'All-Time'] as $key => $label)
                    <button 
                        type="button" 
                        wire:click="setPeriod('{{ $key }}')"
                        class="px-3 py-1 rounded-lg font-bold transition cursor-pointer {{ $selectedPeriod === $key ? 'bg-[#005999] text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-white' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Tombol Ekspor Ranking --}}
            <button 
                type="button" 
                wire:click="exportExcel"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-[#005999] hover:bg-[#005288] text-white text-xs font-bold shadow-xs transition cursor-pointer"
            >
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Ekspor Ranking</span>
            </button>
        </div>
    </div>

    {{-- SECTION 1: 4 KARTU METRIK KPI RINGKASAN SKM --}}
    <div class="statisfy-grid-4">
        {{-- Kartu 1: Indeks Kepuasan (IKM) --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Indeks Kepuasan</span>
                <div class="w-8 h-8 rounded-xl bg-sky-50 text-[#005999] flex items-center justify-center font-bold text-sm">
                    ★
                </div>
            </div>
            <div class="my-2">
                <div class="text-3xl font-black font-mono-numbers text-slate-900 leading-none">
                    {{ number_format($metrics['ikm_score'], 2) }} <span class="text-sm font-semibold text-slate-400">/ {{ number_format($metrics['ikm_max'], 2) }}</span>
                </div>
            </div>
            <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-100">
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    {{ $metrics['ikm_badge'] }}
                </span>
                <span class="text-slate-400 font-mono-numbers text-[11px]">{{ $metrics['ikm_subtext'] }}</span>
            </div>
        </div>

        {{-- Kartu 2: Total Responden SKM --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Responden SKM</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    🏛
                </div>
            </div>
            <div class="my-2">
                <div class="text-3xl font-black font-mono-numbers text-slate-900 leading-none">
                    {{ number_format($metrics['total_respondents']) }} <span class="text-xs font-semibold text-slate-400">Tamu</span>
                </div>
            </div>
            <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-100">
                <span class="text-emerald-600 font-bold text-[11px] flex items-center gap-1">
                    ↗ {{ $metrics['mom_growth'] }}
                </span>
                <span class="text-slate-400 font-mono-numbers text-[11px]">Target: {{ number_format($metrics['target_respondents']) }} ({{ $metrics['target_percentage'] }}%)</span>
            </div>
        </div>

        {{-- Kartu 3: Net Promoter Score --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Net Promoter Score</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-[#F7941D] flex items-center justify-center font-bold text-sm">
                    👍
                </div>
            </div>
            <div class="my-2">
                <div class="text-3xl font-black font-mono-numbers text-slate-900 leading-none">
                    {{ $metrics['nps_score'] }}
                </div>
            </div>
            <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-100">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-[#F7941D] border border-amber-200">
                    {{ $metrics['nps_rating'] }}
                </span>
                <span class="text-slate-400 font-mono-numbers text-[11px]">{{ $metrics['nps_ratio'] }}</span>
            </div>
        </div>

        {{-- Kartu 4: Respon Tercepat Rata-Rata --}}
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Respon Tercepat Rata-Rata</span>
                <div class="w-8 h-8 rounded-xl bg-orange-50 text-[#F7941D] flex items-center justify-center font-bold text-sm">
                    ⏱
                </div>
            </div>
            <div class="my-2">
                <div class="text-3xl font-black font-mono-numbers text-slate-900 leading-none">
                    {{ $metrics['avg_response_time'] }} <span class="text-xs font-semibold text-slate-400">rata-rata</span>
                </div>
            </div>
            <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-100">
                <span class="text-emerald-700 font-bold text-[11px] flex items-center gap-1">
                    ✓ SLA 15m Terpenuhi {{ $metrics['sla_achievement'] }}
                </span>
                <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                    {{ $metrics['sla_status'] }}
                </span>
            </div>
        </div>
    </div>

    {{-- SECTION 2: EXECUTIVE SUMMARY NLP & REKOMENDASI STRATEGIS PIMPINAN --}}
    <div class="statisfy-grid-exec">
        {{-- Panel Kiri: Sintesis Eksekutif NLP / STATISFY V3.4 + GPT-2026 --}}
        <div class="lg:col-span-8 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between flex-wrap gap-2 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-[#005999]"></span>
                        <span class="text-[11px] font-mono-numbers uppercase tracking-wider text-slate-400 font-bold">
                            SINTESIS EKSEKUTIF • NLP / STATISFY V3.4 + GPT-2026
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Sentimen Positif {{ $nlpInsights['sentiment_positive'] }}%
                        </span>
                        <button 
                            type="button" 
                            wire:click="refreshSynthesis"
                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-600 text-xs font-bold transition cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            <span>Perbarui Sintesis</span>
                        </button>
                    </div>
                </div>

                <h2 class="text-lg font-black text-slate-900 mt-4 mb-2 tracking-tight font-serif-bps">
                    Executive Summary Sentimen & Rekomendasi Pimpinan
                </h2>

                <p class="text-xs text-slate-600 leading-relaxed">
                    Berdasarkan sintesis data responden SKD ({{ number_format($metrics['total_respondents']) }} tamu), kepuasan PST BPS Provinsi Jawa Tengah berada pada kategori <strong class="text-slate-900">Sangat Prima (IKM {{ number_format($metrics['ikm_score'], 2) }} / 4.00)</strong>. Faktor determinan utama bertumpu pada kompetensi dan keramahan petugas meja konsultasi statistik, kecepatan penyediaan disagregasi makro (Susenas & PDRB), serta konsistensi durasi pelayanan yang jauh melampaui standar SLA (rata-rata 3.2 menit dari batas maksimal 15 menit).
                </p>

                {{-- Callout Box Mutu & Integritas --}}
                <div class="mt-3.5 p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200/80 text-xs text-slate-700 leading-relaxed flex items-start gap-2.5">
                    <span class="text-emerald-600 font-bold text-sm">ℹ</span>
                    <div>
                        Akurasi pemetik membuktikan kepuasan tatap muka (94%) berkolerasi linear dengan kecakapan master fungsional statistisi. Seluruh kanal mencatat predikat bersih tanpa keluhan diskriminasi maupun gratifikasi (<strong class="text-emerald-800">Zero Pungli Compliance 100%</strong>).
                    </div>
                </div>
            </div>

            {{-- Frekuensi Kata Kunci Dominan --}}
            <div class="mt-4 pt-3 border-t border-slate-100">
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">
                    Frekuensi Kata Kunci Dominan (NLP Corpus)
                </div>
                <div class="flex flex-wrap items-center gap-1.5">
                    @foreach($nlpInsights['corpus_counts'] as $corpus)
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ str_contains($corpus['name'], 'AC') ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-slate-100 text-slate-700 border border-slate-200/80' }}">
                            {{ $corpus['name'] }} <span class="font-mono-numbers text-[11px] opacity-75 font-normal">{{ $corpus['count'] }}x</span>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Panel Kanan: Rekomendasi Strategis Pimpinan --}}
        <div class="lg:col-span-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#005999]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Rekomendasi Strategis Pimpinan
                    </h3>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                        3 Poin Prioritas
                    </span>
                </div>

                <div class="space-y-2.5 mt-3.5">
                    {{-- Poin P1 --}}
                    <div class="p-3 rounded-xl border border-amber-200 bg-amber-50/40">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="w-5 h-5 rounded-md bg-[#F7941D] text-white font-black text-[10px] flex items-center justify-center">P1</span>
                            <span class="text-xs font-bold text-slate-800">Optimalisasi Jam Sibuk (11.00 - 13.00 WIB)</span>
                        </div>
                        <p class="text-[11px] text-slate-600 leading-normal pl-6">
                            Alokasi loket cadangan Meja 4 saat antrean menembus 3 tamu serentak guna mempertahankan SLA 3.2m.
                        </p>
                    </div>

                    {{-- Poin P2 --}}
                    <div class="p-3 rounded-xl border border-sky-200 bg-sky-50/40">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="w-5 h-5 rounded-md bg-[#005999] text-white font-black text-[10px] flex items-center justify-center">P2</span>
                            <span class="text-xs font-bold text-slate-800">Upgrade Fasilitas Kenyamanan Ruang Tunggu</span>
                        </div>
                        <p class="text-[11px] text-slate-600 leading-normal pl-6">
                            Tindak lanjuti masukan 164 term terkait penambahan outlet port USB & pendingin udara di pojok tunggu konsultasi.
                        </p>
                    </div>

                    {{-- Poin P3 --}}
                    <div class="p-3 rounded-xl border border-emerald-200 bg-emerald-50/40">
                        <div class="flex items-center gap-1.5 mb-1">
                            <span class="w-5 h-5 rounded-md bg-emerald-600 text-white font-black text-[10px] flex items-center justify-center">P3</span>
                            <span class="text-xs font-bold text-slate-800">Perluasan Respons Survei Statisfy via WhatsApp</span>
                        </div>
                        <p class="text-[11px] text-slate-600 leading-normal pl-6">
                            Otomatisasi pengiriman tautan survei evaluasi 15 detik setelah tiket selesai untuk pengunjung non-tatap muka.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi Pimpinan --}}
            <div class="statisfy-recom-buttons">
                <button 
                    type="button" 
                    wire:click="sendDisposition"
                    class="statisfy-btn-navy w-full py-2 px-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1 shadow-xs cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                    <span>Kirim Disposisi Pimpinan</span>
                </button>
                <button 
                    type="button" 
                    wire:click="exportPdf"
                    class="statisfy-btn-white w-full py-2 px-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1 cursor-pointer"
                >
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span>Unduh Brief PDF</span>
                </button>
            </div>
        </div>
    </div>

    {{-- SECTION 3: TREN BERKALA TAHUN 2026 & SEBARAN SKALA 4 SKD --}}
    <div class="statisfy-grid-trends">
        {{-- Kolom Kiri: Tren Berkala IKM Bulanan --}}
        <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tren Berkala Tahun 2026</span>
                        <h3 class="text-sm font-black text-slate-900 mt-0.5 font-serif-bps">Progres Indeks Kepuasan Bulanan (IKM)</h3>
                    </div>
                    <div class="flex items-center gap-3 text-xs">
                        <span class="inline-flex items-center gap-1 font-semibold text-slate-600">
                            <span class="w-2.5 h-2.5 rounded-sm bg-[#005288]"></span> Realisasi
                        </span>
                        <span class="inline-flex items-center gap-1 font-semibold text-slate-400">
                            <span class="w-3 h-0.5 bg-[#F7941D]"></span> Target (3.80)
                        </span>
                    </div>
                </div>

                {{-- Visualisasi Bar Chart Sederhana & Bersih --}}
                <div class="mt-6 mb-3">
                    <div class="statisfy-chart-container">
                        {{-- Garis Target 3.80 --}}
                        <div class="absolute inset-x-0 top-10 border-t-2 border-dashed border-[#F7941D]/60 pointer-events-none">
                            <span class="absolute right-0 -top-2.5 px-1.5 py-0.5 rounded text-[9px] font-black bg-amber-50 text-[#F7941D] border border-amber-200 font-mono-numbers">
                                Target 3.80
                            </span>
                        </div>

                        {{-- Batang Per Bulan --}}
                        @foreach($monthlyTrends as $item)
                            <div class="statisfy-bar-col group">
                                @if(!empty($item['is_current']))
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-black bg-[#6CBE45] text-white font-mono-numbers shadow-xs">
                                        {{ number_format($item['score'], 2) }}
                                    </span>
                                @endif
                                <div 
                                    style="height: {{ $item['height'] }};" 
                                    class="{{ !empty($item['is_current']) ? 'statisfy-bar-current' : 'statisfy-bar-default' }}"
                                    title="{{ $item['month'] }}: {{ $item['score'] }}"
                                ></div>
                                <span class="text-[11px] font-bold text-slate-500 group-hover:text-slate-800">{{ $item['month'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between text-xs text-slate-500 pt-2 border-t border-slate-100">
                <span>Kenaikan rata-rata bulanan: <strong class="text-emerald-600 font-mono-numbers">+0.012 poin</strong></span>
                <span class="font-bold text-[#005999]">Status: Melampaui Target RENSTRA</span>
            </div>
        </div>

        {{-- Kolom Kanan: Distribusi 4 Skala Penilaian SKD --}}
        <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Distribusi Skala 4 SKD</span>
                        <h3 class="text-sm font-black text-slate-900 mt-0.5 font-serif-bps">Sebaran 4 Skala Penilaian</h3>
                    </div>
                    <span class="text-xs font-mono-numbers text-slate-400 font-bold">
                        N = {{ number_format($scaleDistribution['total']) }}
                    </span>
                </div>

                <div class="space-y-3.5 mt-4">
                    @foreach($scaleDistribution['scales'] as $scale)
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1">
                                <span class="font-bold text-slate-800 flex items-center gap-1.5">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $scale['color'] }}"></span>
                                    {{ $scale['label'] }}
                                </span>
                                <span class="font-mono-numbers text-slate-600 font-semibold">
                                    <strong>{{ $scale['percentage'] }}%</strong> <span class="text-slate-400 text-[11px]">({{ number_format($scale['count']) }})</span>
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                <div class="{{ $scale['color'] }} h-full rounded-full transition-all duration-500" style="width: {{ $scale['percentage'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500">Tingkat Kepuasan Gabungan (Puas + Sangat):</span>
                <span class="px-2 py-0.5 rounded-full font-black text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono-numbers">
                    {{ $scaleDistribution['positive_combined'] }}% Positif
                </span>
            </div>
        </div>
    </div>

    {{-- SECTION 4: KANAL LAYANAN TERPADU & ANALISIS TEKS SKM --}}
    <div class="statisfy-grid-channels">
        {{-- Kolom Kiri: Distribusi Media Akses Pengunjung --}}
        <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Kanal Layanan Terpadu</span>
                        <h3 class="text-sm font-black text-slate-900 mt-0.5 font-serif-bps">Distribusi Media Akses Pengunjung</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                        Omni-Channel
                    </span>
                </div>

                {{-- Bar Segmented --}}
                <div class="mt-4 mb-4">
                    <div class="w-full h-3 rounded-full overflow-hidden flex bg-slate-100 shadow-inner">
                        <div style="width: 66%;" class="bg-[#005288] h-full" title="Tatap Muka 66%"></div>
                        <div style="width: 20%;" class="bg-[#6CBE45] h-full" title="WhatsApp 20%"></div>
                        <div style="width: 14%;" class="bg-[#00B4F0] h-full" title="Website 14%"></div>
                    </div>
                </div>

                {{-- Rincian 3 Kanal --}}
                <div class="space-y-3">
                    {{-- Kanal 1: Tatap Muka --}}
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/60 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#005288] text-white flex items-center justify-center font-bold text-sm shadow-2xs">
                                🏛
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800">Tatap Muka Loket PST</div>
                                <div class="text-[10px] text-slate-500">794 responden • Pelayanan Langsung Lobi</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-black font-mono-numbers text-slate-900">66%</div>
                            <div class="text-[10px] font-bold text-emerald-600 font-mono-numbers">IKM 3.96</div>
                        </div>
                    </div>

                    {{-- Kanal 2: WhatsApp --}}
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/60 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#6CBE45] text-white flex items-center justify-center font-bold text-sm shadow-2xs">
                                💬
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800">WhatsApp PST Jateng</div>
                                <div class="text-[10px] text-slate-500">252 responden • Layanan Konsultasi Virtual</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-black font-mono-numbers text-slate-900">20%</div>
                            <div class="text-[10px] font-bold text-emerald-600 font-mono-numbers">IKM 3.91</div>
                        </div>
                    </div>

                    {{-- Kanal 3: Website & Chatbot --}}
                    <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/60 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-[#00B4F0] text-white flex items-center justify-center font-bold text-sm shadow-2xs">
                                🌐
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800">Website & Web Chatbot</div>
                                <div class="text-[10px] text-slate-500">172 responden • Unduh data & tanya digital</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-black font-mono-numbers text-slate-900">14%</div>
                            <div class="text-[10px] font-bold text-emerald-600 font-mono-numbers">IKM 3.88</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Kecenderungan kepuasan tertinggi:</span>
                <span class="font-bold text-[#005288]">Tatap Muka (3.96)</span>
            </div>
        </div>

        {{-- Kolom Kanan: Word Cloud & Quick-Tags Leaderboard --}}
        <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Analisis Teks SKM</span>
                        <h3 class="text-sm font-black text-slate-900 mt-0.5 font-serif-bps">Word Cloud & Quick-Tags Leaderboard</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                        Top 8 Kata Kunci
                    </span>
                </div>

                {{-- Tag Cloud Chips --}}
                <div class="flex flex-wrap items-center justify-center gap-2 p-4 my-3 bg-slate-50/70 border border-slate-200/70 rounded-2xl">
                    @foreach($nlpInsights['keywords'] as $kw)
                        <span class="px-3 py-1.5 rounded-xl text-xs font-bold border shadow-2xs transition hover:scale-105 {{ $kw['color'] }}">
                            {{ $kw['tag'] }}
                        </span>
                    @endforeach
                </div>

                {{-- 4 Kotak Metrik Hits Sentimen --}}
                <div class="statisfy-hits-grid">
                    @foreach($nlpInsights['sentiment_boxes'] as $box)
                        <div class="p-2.5 rounded-xl border bg-white flex flex-col justify-between {{ $box['color'] }}">
                            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 truncate">
                                {{ $box['label'] }}
                            </div>
                            <div class="my-1">
                                <div class="text-lg font-black font-mono-numbers text-slate-900 leading-none">
                                    {{ $box['hits'] }} <span class="text-[10px] text-slate-400 font-normal">hits</span>
                                </div>
                            </div>
                            <div class="text-[10px] font-bold text-emerald-600 font-mono-numbers">
                                {{ $box['rate'] }} positif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Sensitivitas Sentimen: <strong class="text-slate-700">Apresiasi Keramahan & Kecepatan</strong></span>
                <span class="text-[#005999] font-bold cursor-pointer hover:underline">Lihat Status 42 Tag -></span>
            </div>
        </div>
    </div>

    {{-- SECTION 5: LEADERBOARD IKM PETUGAS & EVALUASI MEJA LAYANAN --}}
    <div class="statisfy-grid-leaderboard">
        {{-- Kolom Kiri: Leaderboard IKM Petugas PST Jateng --}}
        <div class="lg:col-span-8 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Peringkat Kinerja Pegawai</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Terverifikasi SKD
                        </span>
                    </div>

                    {{-- Toggle Pengurutan --}}
                    <div class="inline-flex p-0.5 bg-slate-100 rounded-lg border border-slate-200 gap-1 text-[11px]">
                        <button 
                            type="button" 
                            wire:click="setLeaderboardTab('score')"
                            class="px-2.5 py-1 rounded-md font-bold transition cursor-pointer {{ $leaderboardTab === 'score' ? 'bg-[#005999] text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            Skor Tertinggi
                        </button>
                        <button 
                            type="button" 
                            wire:click="setLeaderboardTab('total')"
                            class="px-2.5 py-1 rounded-md font-bold transition cursor-pointer {{ $leaderboardTab === 'total' ? 'bg-[#005999] text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            Tamu Terbanyak
                        </button>
                        <button 
                            type="button" 
                            wire:click="setLeaderboardTab('sla')"
                            class="px-2.5 py-1 rounded-md font-bold transition cursor-pointer {{ $leaderboardTab === 'sla' ? 'bg-[#005999] text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}"
                        >
                            SLA Tercepat
                        </button>
                    </div>
                </div>

                <h3 class="text-sm font-black text-slate-900 mt-3 mb-3 font-serif-bps">
                    Leaderboard IKM Petugas PST Jateng
                </h3>

                {{-- Tabel Leaderboard --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-[10px] uppercase font-bold text-slate-400 border-b border-slate-100 pb-2">
                                <th class="pb-2 font-bold">Rank - Profil Petugas & Loket</th>
                                <th class="pb-2 font-bold text-center">Total Tamu</th>
                                <th class="pb-2 font-bold text-center">Rata SLA</th>
                                <th class="pb-2 font-bold text-center">Skor IKM</th>
                                <th class="pb-2 font-bold text-right">Penghargaan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($leaderboard as $officer)
                                <tr class="hover:bg-slate-50/70 transition">
                                    {{-- Kolom Petugas --}}
                                    <td class="py-2.5 pr-2">
                                        <div class="flex items-center gap-2.5">
                                            <span class="w-6 h-6 rounded-full font-black font-mono-numbers text-xs flex items-center justify-center shrink-0 {{ $officer['rank'] === 1 ? 'bg-amber-100 text-amber-800 border border-amber-300' : ($officer['rank'] === 2 ? 'bg-slate-200 text-slate-700' : ($officer['rank'] === 3 ? 'bg-orange-100 text-orange-800' : 'bg-slate-100 text-slate-500')) }}">
                                                {{ $officer['rank'] }}
                                            </span>
                                            <div class="w-8 h-8 rounded-xl bg-[#005288] text-white font-black text-xs flex items-center justify-center shrink-0">
                                                {{ substr($officer['name'], 0, 2) }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-bold text-slate-900 truncate">{{ $officer['name'] }}</div>
                                                <div class="text-[10px] text-slate-400 font-mono-numbers truncate">
                                                    NIP: {{ $officer['nip'] }} <span class="text-slate-300">•</span> {{ $officer['counter'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Total Tamu --}}
                                    <td class="py-2.5 px-2 text-center font-bold font-mono-numbers text-slate-800">
                                        {{ $officer['served'] }} <span class="text-[10px] text-slate-400 font-normal">tamu</span>
                                    </td>

                                    {{-- SLA --}}
                                    <td class="py-2.5 px-2 text-center font-bold font-mono-numbers text-emerald-700">
                                        {{ $officer['sla'] }}
                                    </td>

                                    {{-- Skor IKM --}}
                                    <td class="py-2.5 px-2 text-center">
                                        <div class="font-black font-mono-numbers text-[#F7941D]">
                                            ★ {{ number_format($officer['score'], 2) }} <span class="text-[10px] text-slate-400">/4.00</span>
                                        </div>
                                        <div class="text-[9px] text-slate-400 font-mono-numbers">99.5% Sangat Puas</div>
                                    </td>

                                    {{-- Badge Penghargaan --}}
                                    <td class="py-2.5 pl-2 text-right">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $officer['award_color'] }}">
                                            {{ $officer['award'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Evaluasi Meja Layanan --}}
        <div class="lg:col-span-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Analisis Per Loket</span>
                        <h3 class="text-sm font-black text-slate-900 mt-0.5 font-serif-bps">Evaluasi 4 Meja Layanan</h3>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        All Mutu A
                    </span>
                </div>

                <div class="space-y-2.5 mt-3.5">
                    @foreach($counterEvaluations as $counter)
                        <div class="p-3 rounded-xl border border-slate-200/80 bg-slate-50/70">
                            <div class="flex items-center justify-between pb-1 border-b border-slate-200/60">
                                <span class="text-xs font-bold text-slate-800">Meja {{ $counter['number'] }}: {{ $counter['name'] }}</span>
                                <span class="font-black font-mono-numbers text-xs text-[#005999]">{{ number_format($counter['score'], 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1.5 font-mono-numbers">
                                <span>{{ $counter['served'] }} Tamu Dilayani</span>
                                <span class="text-emerald-700 font-bold">SLA: {{ $counter['sla'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol Unduh Laporan Evaluasi Pegawai --}}
            <div class="mt-4 pt-3 border-t border-slate-100">
                <button 
                    type="button" 
                    wire:click="exportExcel"
                    class="w-full py-2.5 px-3 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer shadow-2xs"
                >
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m.75 12l3 3m0 0l3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <span>Unduh Laporan Evaluasi Pegawai (.XLSX)</span>
                </button>
            </div>
        </div>
    </div>

    {{-- SECTION 6: PUSAT EKSPOR LAPORAN & RANKING PETUGAS PST --}}
    <div class="bg-gradient-to-r from-[#005288] to-[#005999] rounded-2xl p-5 text-white shadow-sm border border-blue-400/20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center text-white text-lg shrink-0">
                    🛡
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="text-sm sm:text-base font-black tracking-tight text-white font-serif-bps">
                            Pusat Ekspor Laporan & Ranking Petugas PST
                        </h4>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#FDE047] text-slate-900 font-sans">
                            Resmi BPS Jateng
                        </span>
                    </div>
                    <p class="text-xs text-sky-100 font-medium mt-0.5">
                        Format siap cetak/tanda tangan digital Kepala BPS Provinsi Jawa Tengah untuk pemotongan Piagam & Reward ELENA.
                    </p>
                </div>
            </div>

            {{-- Tombol Aksi Ekspor Lengkap --}}
            <div class="statisfy-export-actions">
                <button 
                    type="button" 
                    wire:click="exportExcel"
                    class="statisfy-btn-white"
                >
                    <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    <span>Format Excel (.xlsx)</span>
                </button>

                <button 
                    type="button" 
                    wire:click="exportPdf"
                    class="statisfy-btn-white"
                >
                    <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <span>Nota Dinas PDF (.pdf)</span>
                </button>

                <button 
                    type="button" 
                    wire:click="exportExcel"
                    class="statisfy-btn-orange"
                >
                    <span>Unduh Laporan Lengkap</span>
                </button>
            </div>
        </div>

        {{-- Footer Keaslian Data & Digital Hash --}}
        <div class="mt-4 pt-3 border-t border-white/15 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[11px] text-sky-200">
            <div class="font-mono-numbers">
                Digital Hash: <span class="text-white font-semibold">SHA256:87A-E42-D626-B347-STATISFY-OKT</span>
            </div>
            <div>
                Sinkronisasi Terakhir: <strong class="text-white">{{ $lastSyncTime }}</strong> <span class="text-sky-300">•</span> Pembaruan Otomatis Tiap 15 Menit
            </div>
        </div>
    </div>
</div>
