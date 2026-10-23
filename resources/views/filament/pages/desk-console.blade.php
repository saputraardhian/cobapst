<style>
    /* KONSOL MEJA OPERATOR - HIGH CRAFT ANTI-SLOP DESIGN SYSTEM */
    .console-grid-main {
        display: grid;
        grid-template-columns: 5fr 7fr;
        gap: 1.25rem;
    }
    @media (max-width: 1024px) {
        .console-grid-main {
            grid-template-columns: 1fr;
        }
    }

    .console-grid-bottom {
        display: grid;
        grid-template-columns: 7fr 5fr;
        gap: 1.25rem;
    }
    @media (max-width: 1024px) {
        .console-grid-bottom {
            grid-template-columns: 1fr;
        }
    }

    .console-card-surface {
        background-color: #ffffff;
        border-radius: 1rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02), 0 1px 2px -1px rgba(0, 0, 0, 0.02);
    }

    .console-metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.875rem;
    }
    @media (max-width: 1024px) {
        .console-metrics-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
    @media (max-width: 640px) {
        .console-metrics-grid {
            grid-template-columns: 1fr;
        }
    }

    .console-priority-grid {
        display: grid;
        grid-template-columns: minmax(290px, 1.8fr) 1.2fr 1.8fr minmax(160px, 1.1fr);
        gap: 0.75rem;
        align-items: flex-start;
    }
    @media (max-width: 1024px) {
        .console-priority-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
    @media (max-width: 640px) {
        .console-priority-grid {
            grid-template-columns: 1fr;
        }
    }

    .priority-toggle-container {
        background-color: #fef3c7 !important;
        border: 1px solid #fcd34d !important;
    }
    .priority-toggle-active {
        background-color: #F7941D !important;
        color: #ffffff !important;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1) !important;
    }
    .priority-toggle-inactive {
        background-color: transparent !important;
        color: #475569 !important;
    }
    .priority-toggle-inactive:hover {
        background-color: rgba(255, 255, 255, 0.7) !important;
        color: #1e293b !important;
    }
    .btn-priority-submit {
        background-color: #F7941D !important;
        color: #ffffff !important;
    }
    .btn-priority-submit:hover {
        background-color: #d67b0d !important;
    }

    .bg-console-navy {
        background-color: #0B1E33 !important;
        color: #ffffff !important;
        border-color: #1e293b !important;
    }
    .bg-console-blue {
        background-color: #005288 !important;
        color: #ffffff !important;
    }
    .bg-console-blue:hover {
        background-color: #00416c !important;
    }
    .btn-console-blue {
        background-color: #005288 !important;
        color: #ffffff !important;
        border: 1px solid #005288 !important;
    }
    .btn-console-blue:hover {
        background-color: #00416c !important;
    }
    .bg-console-orange {
        background-color: #F7941D !important;
        color: #ffffff !important;
    }
    .bg-console-orange:hover {
        background-color: #d67b0d !important;
    }
    .btn-console-orange {
        background-color: #F7941D !important;
        color: #ffffff !important;
        border: 1px solid #F7941D !important;
    }
    .btn-console-orange:hover {
        background-color: #d67b0d !important;
    }
    .bg-console-green {
        background-color: #10B981 !important;
        color: #ffffff !important;
    }
    .bg-console-green:hover {
        background-color: #059669 !important;
    }
    .btn-console-green {
        background-color: #10B981 !important;
        color: #ffffff !important;
    }
    .btn-console-green:hover {
        background-color: #059669 !important;
    }
    .text-console-blue {
        color: #005288 !important;
    }
    .border-console-blue {
        border-color: #005288 !important;
    }

    .font-mono-numbers {
        font-family: 'JetBrains Mono', monospace;
    }
</style>

<div 
    x-data="{
        seconds: @entangle('durationSeconds'),
        timerRunning: true,
        init() {
            setInterval(() => {
                if (this.timerRunning) {
                    this.seconds++;
                }
            }, 1000);

            window.addEventListener('keydown', (e) => {
                if (e.key === 'F1') {
                    e.preventDefault();
                    $wire.callLobby();
                } else if (e.key === 'F2') {
                    e.preventDefault();
                    $wire.recallLobby();
                } else if (e.key === 'F4') {
                    e.preventDefault();
                    document.getElementById('priority-input-wa')?.focus();
                } else if (e.key === 'F9') {
                    e.preventDefault();
                    $wire.completeAndSendStatisfy();
                } else if (e.ctrlKey && e.key === 'p') {
                    e.preventDefault();
                    $wire.saveAndPrintConsultation();
                }
            });
        },
        formatMinutes() {
            let m = Math.floor(this.seconds / 60);
            return m < 10 ? '0' + m : m;
        },
        formatSeconds() {
            let s = this.seconds % 60;
            return s < 10 ? '0' + s : s;
        }
    }" 
    class="space-y-4 antialiased text-slate-800"
>
    {{-- SUB-BAR HEADER: TOMBOL KEMBALI & BREADCRUMB INTERNAL --}}
    <div class="w-full flex items-center justify-between gap-3 text-xs mb-1">
        <a href="{{ url('/admin') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-700 font-semibold transition shadow-2xs">
            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            <span>Kembali ke Dashboard Utama</span>
        </a>

        <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
            <span>Admin Internal</span>
            <span class="text-slate-300 font-bold">&gt;</span>
            <span class="font-bold text-slate-800">Konsol Meja Operator (Meja 2)</span>
        </div>
    </div>

    {{-- ROW 1: 4 KARTU METRIK OPERASIONAL CEPAT --}}
    <div class="console-metrics-grid">
        {{-- METRIK 1: DILAYANI HARI INI --}}
        <div class="console-card-surface p-4 flex items-center justify-between gap-3">
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Dilayani Hari Ini</span>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black font-mono-numbers text-slate-900">{{ $metrics['dilayani_hari_ini'] }}</span>
                    <span class="text-xs text-slate-500 font-semibold">Tamu</span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center shrink-0 shadow-2xs">
                <x-console-icon name="team" class="w-5 h-5" />
            </div>
        </div>

        {{-- METRIK 2: RATA-RATA DURASI --}}
        <div class="console-card-surface p-4 flex items-center justify-between gap-3">
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Rata-Rata Durasi</span>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black font-mono-numbers text-emerald-600">{{ $metrics['rata_rata_durasi'] }}</span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center shrink-0 shadow-2xs">
                <x-console-icon name="duration" class="w-5 h-5" />
            </div>
        </div>

        {{-- METRIK 3: INDEKS STATISFY --}}
        <div class="console-card-surface p-4 flex items-center justify-between gap-3">
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Indeks Statisfy</span>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="text-2xl font-black font-mono-numbers text-amber-600">{{ $metrics['indeks_statisfy'] }}</span>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                        ★ {{ $metrics['indeks_scale'] }}
                    </span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center shrink-0 shadow-2xs">
                <x-console-icon name="satisfaction" class="w-5 h-5" />
            </div>
        </div>

        {{-- METRIK 4: SISA ANTREAN MEJA 2 --}}
        <div class="console-card-surface p-4 flex items-center justify-between gap-3">
            <div>
                <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">Sisa Antrean Meja 2</span>
                <div class="flex items-baseline gap-1 mt-0.5">
                    <span class="text-2xl font-black font-mono-numbers text-orange-600">{{ count($waitingQueue) }}</span>
                    <span class="text-xs text-slate-500 font-semibold">Menunggu</span>
                </div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 border border-orange-100 flex items-center justify-center shrink-0 shadow-2xs">
                <x-console-icon name="queue-stack" class="w-5 h-5" />
            </div>
        </div>
    </div>

    {{-- ROW 2: AREA KERJA OPERATOR UTAMA (2 KOLOM: SPOTLIGHT ANTREAN & FORMULIR TAMU) --}}
    <div class="console-grid-main">
        {{-- KOLOM KIRI (5/12): KARTU ANTREAN AKTIF, STOPWATCH, DAN DAFTAR ANTREAN MENUNGGU --}}
        <div class="space-y-4">
            {{-- KOTAK 1: SPOTLIGHT ANTREAN AKTIF & STOPWATCH DURASI --}}
            <div class="console-card-surface p-5 sm:p-6 flex flex-col justify-between">
                <div>
                    {{-- Baris Status Sesi & Badge Loket --}}
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                {{ $activeTicket['status_label'] }}
                            </span>
                            <span class="text-xs text-slate-400 font-medium">Mulai: {{ $activeTicket['start_time'] }}</span>
                        </div>
                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-black bg-sky-100 text-sky-800 border border-sky-200">
                            {{ $activeTicket['counter_label'] }}
                        </span>
                    </div>

                    {{-- Penampil Nomor Tiket Jumbo --}}
                    <div class="text-center py-2">
                        <div class="text-[10px] uppercase tracking-widest font-extrabold text-slate-400">Nomor Antrean Aktif</div>
                        <div class="text-5xl sm:text-6xl font-black font-mono-numbers text-console-blue tracking-tight my-1">
                            {{ $activeTicket['ticket_number'] }}
                        </div>
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 mt-0.5">
                            {{ $activeTicket['service_type'] }}
                        </div>
                    </div>

                    {{-- Kotak Stopwatch Inset Gelap (Deep Navy Surface) --}}
                    <div class="mt-4 p-4 rounded-xl bg-console-navy text-white border border-slate-800 shadow-inner">
                        <div class="flex items-center justify-between text-xs mb-1">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-sky-400 animate-pulse"></span>
                                <span class="font-bold text-slate-200 text-[11px]">Stopwatch Durasi Layanan</span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-sky-950 text-sky-400 border border-sky-800">
                                    Waktu Berjalan
                                </span>
                            </div>
                            <span class="text-[10px] font-bold text-amber-400">Target SLA: {{ $targetSlaMinutes }} Menit</span>
                        </div>

                        {{-- Tampilan Timer Eksplisit --}}
                        <div class="text-center py-2.5">
                            <div class="text-3xl sm:text-4xl font-black font-mono-numbers tracking-widest text-white">
                                <span x-text="formatMinutes()">14</span> <span class="text-sky-400 font-normal text-2xl">MENIT</span> : <span x-text="formatSeconds()">35</span> <span class="text-sky-400 font-normal text-2xl">DETIK</span>
                            </div>
                        </div>

                        {{-- Baris Batas SLA & Indikator Aman --}}
                        <div class="flex items-center justify-between pt-2 border-t border-slate-800/80 text-[10px]">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-950/80 text-emerald-400 border border-emerald-800 font-bold">
                                ★ Sisa 15m 25s (Aman)
                            </span>
                            <span class="text-slate-400">48% dari kuota 30 menit</span>
                        </div>
                        <div class="w-full bg-slate-800 h-1 rounded-full mt-2 overflow-hidden">
                            <div class="bg-sky-400 h-full rounded-full transition-all duration-500" style="width: 48%;"></div>
                        </div>
                    </div>
                </div>

                {{-- Matriks Tombol Aksi Petugas --}}
                <div class="mt-4 pt-3 border-t border-slate-100 space-y-2">
                    <div class="grid grid-cols-2 gap-2">
                        <button 
                            type="button" 
                            wire:click="callLobby"
                            class="py-2.5 px-3 rounded-xl btn-console-blue text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer"
                            title="Shortcut: [F1]"
                        >
                            <svg class="w-4 h-4 shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.757 3.63 8.25 4.51 8.25H6.75z" />
                            </svg>
                            <span>Panggil Lobi</span>
                        </button>

                        <button 
                            type="button" 
                            wire:click="recallLobby"
                            class="py-2.5 px-3 rounded-xl btn-console-orange text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer"
                            title="Shortcut: [F2]"
                        >
                            <svg class="w-4 h-4 shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            <span>Recall Suara</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <button 
                            type="button" 
                            wire:click="startServing"
                            class="py-2 px-3 rounded-xl border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer"
                        >
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                            </svg>
                            <span>Mulai Layani</span>
                        </button>

                        <a 
                            href="#panel-prioritas"
                            class="py-2 px-3 rounded-xl border border-amber-200 bg-amber-50 hover:bg-amber-100/80 text-amber-900 text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-2xs"
                        >
                            <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Sisip Prioritas</span>
                        </a>
                    </div>

                    {{-- Tombol Utama: Selesai & Kirim Survei Statisfy --}}
                    <button 
                        type="button" 
                        wire:click="completeAndSendStatisfy"
                        class="w-full py-3 px-4 rounded-xl btn-console-green text-sm font-extrabold transition flex items-center justify-center gap-2 shadow-xs cursor-pointer"
                        title="Shortcut: [F9]"
                    >
                        <svg class="w-5 h-5 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Selesai &amp; Kirim Survei Statisfy</span>
                    </button>
                </div>
            </div>

            {{-- KOTAK 2: ANTREAN MENUNGGU MEJA 2 --}}
            <div class="console-card-surface p-4 sm:p-5">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-xs font-extrabold text-slate-900">Antrean Menunggu Meja 2</h2>
                        <p class="text-[10px] text-slate-500 font-medium">{{ count($waitingQueue) }} tiket dalam antrean lobi</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                        Estimasi: 45m
                    </span>
                </div>

                {{-- Daftar Tiket Menunggu --}}
                <div class="mt-3 space-y-2.5">
                    @forelse($waitingQueue as $queue)
                        <div class="p-3 rounded-xl border {{ $queue['is_priority'] ? 'border-amber-200 bg-amber-50/40' : 'border-slate-200/80 bg-white hover:bg-slate-50/70' }} transition flex items-center justify-between gap-3 shadow-2xs">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-lg {{ $queue['is_priority'] ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-blue-50 text-blue-800 border border-blue-200' }} flex items-center justify-center font-mono-numbers font-black text-xs shrink-0 shadow-2xs">
                                    {{ $queue['ticket_number'] }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="font-bold text-slate-900 text-xs truncate">{{ $queue['visitor_name'] }}</span>
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-bold {{ $queue['is_priority'] ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                            {{ $queue['category'] }}
                                        </span>
                                    </div>
                                    <p class="text-[10px] text-slate-500 truncate mt-0.5">{{ $queue['institution'] }} • {{ $queue['topic'] }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-[10px] {{ $queue['is_priority'] ? 'text-amber-700 font-bold' : 'text-slate-400' }} block">{{ $queue['wait_time'] }}</span>
                                <button 
                                    type="button" 
                                    wire:click="callTicketFromQueue('{{ $queue['ticket_number'] }}')"
                                    class="text-xs font-bold text-[#005288] hover:underline cursor-pointer flex items-center gap-0.5 ml-auto mt-0.5"
                                >
                                    <span>Panggil</span>
                                    <span>-&gt;</span>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs text-slate-400">
                            Semua antrean loket telah selesai dilayani.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN (7/12): FORMULIR PELAYANAN TAMU TERPADU (ZERO-RELOAD) --}}
        <div class="console-card-surface p-5 sm:p-6 flex flex-col justify-between">
            <div class="space-y-4">
                {{-- Header Formulir --}}
                <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-[#005288] block">Pencatatan Tamu Mandiri • Zero Reload</span>
                        <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Formulir Pelayanan Tamu Terpadu</h2>
                    </div>
                    <div class="flex items-center gap-1.5">
                        @if(!empty($lastSavedTime))
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Tersimpan otomatis {{ $lastSavedTime }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-50 text-slate-500 border border-slate-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Formulir Siap Diisi
                            </span>
                        @endif
                        <button type="button" wire:click="searchVisitorByPhone" class="p-1 text-slate-400 hover:text-slate-600 transition cursor-pointer" title="Perbarui / Sinkronisasi Data">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Field 1: Nomor WhatsApp / Handphone Pengunjung --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Nomor WhatsApp / Handphone Pengunjung</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.500ms="guestForm.phone"
                            wire:change="searchVisitorByPhone"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:outline-none focus:ring-2 focus:ring-[#005288]/30 focus:border-[#005288]" 
                            placeholder="Contoh: 0812-3456-7890"
                        />

                        {{-- Lencana Status Verifikasi Master PST (Muncul hanya saat nomor diisi) --}}
                        <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                            @if(!empty($guestForm['phone']))
                                @if($isVisitorRegistered)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 pointer-events-auto">
                                        <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        <span>Terdaftar di Master PST</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 pointer-events-auto">
                                        Tamu Baru
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Notifikasi Riwayat Auto-Filled --}}
                    @if(!empty($visitorHistory) && !empty($guestForm['phone']))
                    <div class="mt-1.5 flex items-center justify-between text-[10px] text-slate-500 font-medium">
                        <span class="flex items-center gap-1 text-slate-600 truncate">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                            <span>{{ $visitorHistory }}</span>
                        </span>
                        <span class="font-bold text-[#005288] shrink-0 ml-2">Auto-Filled ↗</span>
                    </div>
                    @endif
                </div>

                {{-- Row 2 Kolom: Nama Lengkap & Instansi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Nama Lengkap Pengunjung</label>
                        <input 
                            type="text" 
                            wire:model="guestForm.name"
                            placeholder="Nama lengkap pengunjung..."
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:outline-none focus:ring-2 focus:ring-[#005288]/30 focus:border-[#005288]" 
                        />
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Instansi / Perguruan Tinggi</label>
                        <input 
                            type="text" 
                            wire:model="guestForm.institution"
                            placeholder="Contoh: Universitas Diponegoro / Bappeda Prov. Jateng..."
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:outline-none focus:ring-2 focus:ring-[#005288]/30 focus:border-[#005288]" 
                        />
                    </div>
                </div>

                {{-- Row 2 Kolom: Kategori Pengunjung & Kunjungan Pertama? --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Kategori Pengunjung</label>
                        <div class="grid grid-cols-2 gap-1.5">
                            @foreach(['Mahasiswa', 'Dosen / Peneliti', 'Pemerintah / OPD', 'Swasta'] as $cat)
                                <button 
                                    type="button" 
                                    wire:click="$set('guestForm.category', '{{ $cat }}')"
                                    class="py-1.5 px-2 rounded-lg text-[11px] font-bold transition flex items-center justify-center gap-1 cursor-pointer {{ $guestForm['category'] === $cat ? 'btn-console-blue shadow-xs' : 'border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-700' }}"
                                >
                                    <span>{{ $cat }}</span>
                                    @if($guestForm['category'] === $cat)
                                        <svg class="w-3 h-3 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1.5">Kunjungan Pertama?</label>
                        <div class="grid grid-cols-2 gap-1.5">
                            <button 
                                type="button" 
                                wire:click="$set('guestForm.is_first_visit', true)"
                                class="py-1.5 px-3 rounded-lg text-xs font-bold transition flex items-center justify-center cursor-pointer {{ $guestForm['is_first_visit'] === true ? 'btn-console-blue shadow-xs' : 'border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-700' }}"
                            >
                                Ya
                            </button>
                            <button 
                                type="button" 
                                wire:click="$set('guestForm.is_first_visit', false)"
                                class="py-1.5 px-3 rounded-lg text-xs font-bold transition flex items-center justify-center cursor-pointer {{ $guestForm['is_first_visit'] === false ? 'btn-console-blue shadow-xs' : 'border border-slate-200/90 bg-white hover:bg-slate-50 text-slate-700' }}"
                            >
                                Tidak
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Field: Topik Konsultasi & Permohonan Data --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Topik Konsultasi &amp; Permohonan Data</label>
                    <input 
                        type="text" 
                        wire:model="guestForm.topic"
                        placeholder="Contoh: Konsultasi Data Kemiskinan Makro / PDRB Triwulanan..."
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:outline-none focus:ring-2 focus:ring-[#005288]/30 focus:border-[#005288]" 
                    />
                </div>

                {{-- Field: Dataset Diminta (Multi-Pill Tags) --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1.5">DATASET DIMINTA</label>
                    <div class="flex items-center flex-wrap gap-1.5">
                        @forelse($guestForm['requested_datasets'] as $index => $dataset)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                <span>{{ $dataset }}</span>
                                <button type="button" wire:click="removeDataset({{ $index }})" class="hover:text-rose-600 transition text-xs font-black cursor-pointer">×</button>
                            </span>
                        @empty
                            <span class="text-[11px] text-slate-400 italic">Belum ada dataset yang ditambahkan. Ketik nama dataset di samping lalu tekan Enter.</span>
                        @endforelse

                        {{-- Input Tambah Dataset Cepat --}}
                        <div class="inline-flex items-center gap-1 w-full sm:w-auto mt-1 sm:mt-0">
                            <input 
                                type="text" 
                                wire:model="newDatasetInput"
                                wire:keydown.enter.prevent="addDataset"
                                placeholder="+ Tambah Dataset (Enter)..." 
                                class="px-2.5 py-1.5 rounded-lg border border-dashed border-slate-300 text-[11px] text-slate-700 bg-white placeholder:text-slate-400 focus:outline-none focus:border-[#005288] w-48"
                            />
                            @if(!empty($newDatasetInput))
                            <button type="button" wire:click="addDataset" class="px-2.5 py-1.5 rounded btn-console-blue text-[10px] font-bold cursor-pointer">
                                + Tambah
                            </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Textarea: Catatan Konsultasi & Penjelasan Petugas --}}
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Catatan Konsultasi &amp; Resume Layanan</label>
                    <textarea 
                        wire:model="guestForm.consultation_notes"
                        rows="3"
                        placeholder="Tuliskan resume konsultasi, materi pembahasan, atau penjelasan layanan kepada tamu..."
                        class="w-full p-2.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005288]/30 focus:border-[#005288] leading-relaxed"
                    ></textarea>
                </div>

                {{-- Inset Box: Catatan Internal Petugas (Rahasia & Monev) --}}
                <div class="p-3 rounded-xl border border-amber-200 bg-amber-50/50 space-y-1.5">
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="font-bold text-amber-800 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                            <span>Catatan Internal Petugas (Rahasia &amp; Monev)</span>
                        </span>
                        <span class="text-slate-400">Hanya dapat dibaca Tim Evaluasi ELENA</span>
                    </div>
                    <textarea 
                        wire:model="guestForm.internal_notes"
                        rows="2"
                        placeholder="Catatan khusus evaluasi internal petugas loket..."
                        class="w-full p-2 rounded-lg border border-amber-200/90 bg-white text-xs text-slate-700 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-amber-400 leading-snug"
                    ></textarea>
                </div>

                {{-- Radio Hasil Disposisi & Checkbox Salinan WhatsApp --}}
                <div class="pt-2 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="font-bold text-slate-700 text-[11px]">Hasil Disposisi:</span>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer font-medium text-slate-700">
                            <input type="radio" wire:model="guestForm.disposition" value="loket" class="text-[#005288] focus:ring-[#005288]" />
                            <span>Diberikan Langsung di Loket</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer font-medium text-slate-700">
                            <input type="radio" wire:model="guestForm.disposition" value="lanjutan" class="text-[#005288] focus:ring-[#005288]" />
                            <span>Perlu Pengolahan Lanjutan</span>
                        </label>
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700">
                            <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            <span>Tanda Terima PDF Siap Dibuat</span>
                        </span>
                    </div>
                </div>

                {{-- Checkbox WhatsApp & Tombol Simpan Cetak --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                        <input type="checkbox" wire:model="guestForm.send_whatsapp_copy" class="rounded text-[#005288] focus:ring-[#005288]" />
                        <span>Kirim salinan PDF otomatis ke WhatsApp pengunjung</span>
                    </label>

                    <button 
                        type="button" 
                        wire:click="saveAndPrintConsultation"
                        class="py-2.5 px-5 rounded-xl btn-console-blue text-xs font-extrabold transition flex items-center justify-center gap-2 shadow-xs cursor-pointer shrink-0"
                        title="Shortcut: [Ctrl+P]"
                    >
                        <svg class="w-4 h-4 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75V4.5a2.25 2.25 0 012.25-2.25h6a2.25 2.25 0 012.25 2.25v2.25m-10.5 0h10.5m-10.5 0H4.5A2.25 2.25 0 002.25 9v6.75c0 .621.504 1.125 1.125 1.125H6.75m0 0v3.75A2.25 2.25 0 009 22.875h6a2.25 2.25 0 002.25-2.25v-3.75m-10.5 0h10.5m0 0h2.625c.621 0 1.125-.504 1.125-1.125V9a2.25 2.25 0 00-2.25-2.25h-1.875" />
                        </svg>
                        <span>Simpan &amp; Cetak Bukti Konsultasi</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 3: PANEL SISIP ANTREAN PRIORITAS & FAST-TRACK (BYPASS MEJA 2) --}}
    <div id="panel-prioritas" class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-amber-50/90 to-orange-50/90 border border-amber-200/90 shadow-2xs space-y-3">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-[#F7941D] text-white flex items-center justify-center shrink-0 shadow-2xs">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-black text-slate-900 text-xs sm:text-sm">Panel Sisip Antrean Prioritas &amp; Fast-Track (Bypass Meja 2)</h3>
                        <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300">
                            SOP PELAYANAN INKLUSIF
                        </span>
                    </div>
                    <p class="text-[10px] text-slate-500 font-medium">Sisipkan antrean darurat atau prioritas langsung ke urutan berikutnya tanpa merusak penomoran tiket lobi.</p>
                </div>
            </div>

            <div class="text-left sm:text-right">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-mono-numbers font-black bg-amber-100/90 text-amber-900 border border-amber-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#F7941D]"></span>
                    <span>Nomor Siap Diterbitkan: {{ $nextPriorityNumber }}</span>
                </span>
            </div>
        </div>

        {{-- Form Sisip 4 Elemen Grid --}}
        <div class="console-priority-grid pt-1">
            <div>
                <label class="block text-[10px] uppercase font-bold text-slate-500 mb-1.5 tracking-wider">Kategori Prioritas / Fast-Track</label>
                <div class="priority-toggle-container p-1 rounded-xl flex gap-1 h-10 items-center">
                    <button 
                        type="button" 
                        wire:click="$set('priorityForm.category', 'Disabilitas')"
                        class="flex-1 h-full px-2 rounded-lg text-xs font-bold transition flex items-center justify-center gap-1.5 whitespace-nowrap {{ $priorityForm['category'] === 'Disabilitas' ? 'priority-toggle-active' : 'priority-toggle-inactive' }}"
                    >
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="15" cy="4" r="1.5" fill="currentColor"/>
                            <path d="M9 19a5 5 0 1 0 5-5H9V9h6l2.5 5"/>
                            <path d="M9 14h5"/>
                        </svg>
                        <span>Disabilitas</span>
                    </button>
                    <button 
                        type="button" 
                        wire:click="$set('priorityForm.category', 'Lansia (>60 Th)')"
                        class="flex-1 h-full px-2 rounded-lg text-xs font-bold transition flex items-center justify-center gap-1.5 whitespace-nowrap {{ $priorityForm['category'] === 'Lansia (>60 Th)' ? 'priority-toggle-active' : 'priority-toggle-inactive' }}"
                    >
                        <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="4" r="1.8" fill="currentColor"/>
                            <path d="M9 20l2-6-1.5-2.5a2 2 0 0 1 2-2.5h2a2 2 0 0 1 2 2v4"/>
                            <path d="M17.5 10a1.5 1.5 0 0 1 1.5 1.5V20"/>
                        </svg>
                        <span>Lansia (>60 Th)</span>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-[10px] uppercase font-bold text-slate-500 mb-1.5 tracking-wider">Nomor WA / Identitas Tamu</label>
                <input 
                    id="priority-input-wa"
                    type="text" 
                    wire:model="priorityForm.identifier"
                    placeholder="08XX-XXXX-XXXX atau NIK" 
                    class="w-full h-10 px-3.5 rounded-xl border border-amber-200/90 bg-white text-xs font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:outline-none focus:ring-2 focus:ring-[#F7941D]/30 focus:border-[#F7941D]"
                />
            </div>

            <div>
                <label class="block text-[10px] uppercase font-bold text-slate-500 mb-1.5 tracking-wider">Keperluan Mendesak</label>
                <input 
                    type="text" 
                    wire:model="priorityForm.urgency"
                    placeholder="Contoh: Konsultasi Angka Stunting mendesak" 
                    class="w-full h-10 px-3.5 rounded-xl border border-amber-200/90 bg-white text-xs font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-normal focus:outline-none focus:ring-2 focus:ring-[#F7941D]/30 focus:border-[#F7941D]"
                />
            </div>

            <div>
                <label class="block text-[10px] uppercase font-bold text-slate-500 mb-1.5 tracking-wider">Tindakan Eksekusi</label>
                <button 
                    type="button" 
                    wire:click="insertPriorityQueue"
                    class="w-full h-10 px-4 rounded-xl btn-priority-submit text-white text-xs font-extrabold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer"
                    title="Shortcut: [F4]"
                >
                    <svg class="w-3.5 h-3.5 shrink-0 fill-current" viewBox="0 0 24 24">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                    </svg>
                    <span>Sisipkan {{ $nextPriorityNumber }}</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ROW 4: DUA KARTU BAWAH (PRATINJAU TANDA TERIMA & MODUL STATISFY) --}}
    <div class="console-grid-bottom">
        {{-- KARTU KIRI: PRATINJAU BUKTI LAYANAN KONSULTASI RESMI --}}
        <div class="console-card-surface p-4 sm:p-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-2.5 border-b border-slate-100 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <h4 class="font-bold text-slate-800">Pratinjau Bukti Layanan Konsultasi Resmi</h4>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-mono-numbers text-[10px] text-slate-400 font-semibold">{{ $receiptPreview['receipt_number'] }}</span>
                        <button type="button" wire:click="downloadPdfReceipt" class="font-bold text-[#005288] hover:underline cursor-pointer flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            <span>Unduh PDF</span>
                        </button>
                    </div>
                </div>

                {{-- Inset Dokumen Bukti Fisik Pelayanan --}}
                <div class="mt-3 p-4 rounded-xl border border-slate-200/90 bg-[#F8FAFC] space-y-2.5 text-xs shadow-2xs">
                    <div class="flex items-start justify-between border-b border-slate-200/80 pb-2">
                        <div>
                            <div class="font-black tracking-wider text-slate-900 text-xs">PELAYANAN STATISTIK TERPADU (PST)</div>
                            <div class="text-[9px] font-bold text-slate-500 uppercase">BADAN PUSAT STATISTIK PROVINSI JAWA TENGAH</div>
                        </div>
                        <div class="text-right">
                            <div class="text-[10px] text-slate-400 font-mono-numbers">{{ $receiptPreview['timestamp'] }}</div>
                            <span class="text-[10px] font-black text-emerald-600">STATUS: {{ $receiptPreview['status'] }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                        <div>
                            <span class="text-slate-400 block text-[10px]">Nomor Antrean:</span>
                            <strong class="font-mono-numbers text-slate-800">{{ $receiptPreview['ticket_number'] }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Petugas Jaga:</span>
                            <strong class="text-slate-800">{{ $receiptPreview['officer_name'] }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Nama Tamu:</span>
                            <strong class="text-slate-800">{{ $receiptPreview['guest_name'] }}</strong>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px]">Instansi:</span>
                            <strong class="text-slate-800">{{ $receiptPreview['institution'] }}</strong>
                        </div>
                    </div>

                    <div class="pt-1.5 border-t border-slate-200/60">
                        <span class="text-slate-400 block text-[10px]">Topik Data:</span>
                        <p class="text-slate-700 font-semibold text-[11px] leading-tight">{{ $receiptPreview['topic'] }}</p>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-slate-200/80 text-[9px] text-slate-400 font-mono-numbers">
                        <span class="flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>Digital Signature Verified: {{ $receiptPreview['system_id'] }}</span>
                        </span>
                        <span>Hash: {{ $receiptPreview['signature_hash'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- KARTU KANAN: MODUL STATISFY TERINTEGRASI (DEEP NAVY CARD) --}}
        <div class="p-5 rounded-2xl bg-console-navy text-white border border-slate-800 shadow-xs flex flex-col justify-between">
            <div class="space-y-3">
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        <span class="font-black text-[11px] tracking-wider uppercase text-slate-200">Modul Statisfy Terintegrasi</span>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-sky-950 text-sky-400 border border-sky-800">
                        Survei 15 Detik
                    </span>
                </div>

                <div>
                    <h4 class="text-sm font-black text-white">Trigger Otomatis Evaluasi Pengunjung</h4>
                    <p class="text-xs text-slate-300 leading-relaxed mt-1">
                        Saat tombol <span class="font-bold text-emerald-400">Selesai &amp; Kirim Survei Statisfy</span> ditekan, tautan survei kepuasan 15 detik akan dikirimkan serentak via WhatsApp &amp; QR Kiosk ke smartphone tamu.
                    </p>
                </div>

                {{-- Matriks Integrasi Gateway --}}
                <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-800 space-y-1.5 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-[11px]">WhatsApp Gateway:</span>
                        <span class="font-mono-numbers text-emerald-400 font-bold text-[11px]">0812-3456-7890 (Terkoneksi)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-[11px]">Integrasi ELENA:</span>
                        <span class="font-mono-numbers text-amber-400 font-bold text-[11px]">+0.25 Bobot Kinerja</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 text-[11px]">Status Respon:</span>
                        <span class="text-slate-300 text-[11px] font-medium">Siap kirim saat sesi ditutup</span>
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between text-[10px] text-slate-400 mt-3">
                <span>SOP PermenPAN-RB No. 14 / 2017</span>
                <span class="font-bold text-slate-300">Indeks SKD &amp; PST</span>
            </div>
        </div>
    </div>

    {{-- FOOTER BAR: STATUS KONEKSI SERVER, HARDWARE LOKET, DAN HOTKEYS --}}
    <div class="w-full flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 px-4 py-2.5 bg-white rounded-xl border border-slate-200/80 text-[11px] text-slate-500 shadow-2xs">
        <div class="flex items-center gap-2.5 flex-wrap">
            <span class="inline-flex items-center gap-1.5 font-bold text-slate-700">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>WebSocket: Terhubung (14ms)</span>
            </span>
            <span class="text-slate-300">•</span>
            <span class="inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.757 3.63 8.25 4.51 8.25H6.75z" />
                </svg>
                <span>Audio Lobi: <strong class="text-slate-700 font-medium">Aktif (85%)</strong></span>
            </span>
            <span class="text-slate-300">•</span>
            <span class="inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75V4.5a2.25 2.25 0 012.25-2.25h6a2.25 2.25 0 012.25 2.25v2.25m-10.5 0h10.5m-10.5 0H4.5A2.25 2.25 0 002.25 9v6.75c0 .621.504 1.125 1.125 1.125H6.75m0 0v3.75A2.25 2.25 0 009 22.875h6a2.25 2.25 0 002.25-2.25v-3.75m-10.5 0h10.5m0 0h2.625c.621 0 1.125-.504 1.125-1.125V9a2.25 2.25 0 00-2.25-2.25h-1.875" />
                </svg>
                <span>Printer Slip: <strong class="text-emerald-700 font-bold">Epson Ready</strong></span>
            </span>
        </div>

        {{-- Hotkey Shortcuts --}}
        <div class="flex items-center gap-1.5 flex-wrap text-[10px]">
            <span class="text-slate-400 font-medium mr-1">Hotkey:</span>
            <button type="button" wire:click="callLobby" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold border border-slate-200 cursor-pointer transition">[F1] Panggil</button>
            <button type="button" wire:click="recallLobby" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold border border-slate-200 cursor-pointer transition">[F2] Recall</button>
            <a href="#panel-prioritas" class="px-2 py-0.5 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold border border-slate-200 transition">[F4] Sisip</a>
            <button type="button" wire:click="completeAndSendStatisfy" class="px-2 py-0.5 rounded bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-bold border border-emerald-300 cursor-pointer transition">[F9] Selesai</button>
            <button type="button" wire:click="saveAndPrintConsultation" class="px-2 py-0.5 rounded bg-sky-100 hover:bg-sky-200 text-sky-800 font-bold border border-sky-300 cursor-pointer transition">[Ctrl+P] Cetak</button>
        </div>
    </div>
</div>
