<div 
    x-data="{
        currentTime: '',
        currentDate: '',
        initClock() {
            const update = () => {
                const now = new Date();
                this.currentTime = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) + ' WIB';
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                this.currentDate = now.toLocaleDateString('id-ID', options);
            };
            update();
            setInterval(update, 1000);
        },
        playChime() {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const notes = [523.25, 659.25, 783.99]; // C5, E5, G5
                notes.forEach((freq, idx) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.value = freq;
                    gain.gain.setValueAtTime(0.2, ctx.currentTime + (idx * 0.15));
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + (idx * 0.15) + 0.4);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(ctx.currentTime + (idx * 0.15));
                    osc.stop(ctx.currentTime + (idx * 0.15) + 0.4);
                });
            } catch(e) {}
        },
        speak(text) {
            if (!'speechSynthesis' in window) return;
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'id-ID';
            utterance.rate = 0.95;
            utterance.pitch = 1.05;
            window.speechSynthesis.speak(utterance);
        }
    }"
    x-init="
        initClock();
        window.addEventListener('ticket-issued', (e) => {
            playChime();
            if (e.detail[0]?.audio) {
                setTimeout(() => speak(e.detail[0].speech), 450);
            }
        });
        window.addEventListener('guest-registered', (e) => {
            playChime();
            if (e.detail[0]?.audio) {
                setTimeout(() => speak(e.detail[0].speech), 450);
            }
        });
        window.addEventListener('officer-called', () => playChime());
        window.addEventListener('reprint-ticket', () => window.print());
    "
    class="min-h-screen flex flex-col justify-between bg-[#F8FAFC] text-slate-800 antialiased"
>
    {{-- TOP CONTAINER: HEADER & MAIN WORKSPACE --}}
    <div class="w-full">
        {{-- HEADER KIOSK LOBI (DEEP NAVY WITH WARM ORANGE ACCENT) --}}
        <header class="w-full bg-[#005288] text-white shadow-md border-b-4 border-[#F7941D] px-6 py-4">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4">
                {{-- SISI KIRI: IDENTITAS KELEMBAGAAN & BADGE LOBI --}}
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center shadow-inner shrink-0">
                        <svg class="w-7 h-7 text-[#FDE047]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold tracking-wider bg-white/15 text-amber-300 border border-white/15">
                                LOBI UTAMA PST
                            </span>
                            <span class="text-xs text-slate-200 font-medium">Badan Pusat Statistik Provinsi Jawa Tengah</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white flex items-center gap-2">
                            Kios Antrian Mandiri PST
                        </h1>
                        <p class="text-xs text-sky-100 font-normal mt-0.5">
                            Pelayanan Statistik Terpadu - Cepat, Transparan, Akuntabel dan Bebas Biaya
                        </p>
                    </div>
                </div>

                {{-- SISI KANAN: STATUS OPERASIONAL, JAM DIGITAL, & AKSESIBILITAS --}}
                <div class="flex items-center gap-4 shrink-0">
                    <div class="text-right">
                        <div class="flex items-center justify-end gap-1.5 mb-0.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-[11px] font-bold text-emerald-300 tracking-wider">LAYANAN AKTIF</span>
                        </div>
                        <div class="font-mono-numbers text-2xl font-black tracking-wider text-white" x-text="currentTime">
                            08:42:15 WIB
                        </div>
                        <div class="text-[11px] text-sky-200" x-text="currentDate + ' - Jam Layanan: 08:00 - 15:30'">
                            Senin, 19 Oktober 2026 - Jam Layanan: 08:00 - 15:30
                        </div>
                    </div>

                    <div class="h-10 w-px bg-white/20 hidden sm:block"></div>

                    {{-- TOMBOL AKSESIBILITAS AUDIO & BANTUAN --}}
                    <div class="flex items-center gap-2">
                        <button 
                            type="button" 
                            wire:click="toggleAudio" 
                            class="px-3 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-sm {{ $audioEnabled ? 'bg-white/20 hover:bg-white/30 text-white' : 'bg-rose-500/30 text-rose-200 hover:bg-rose-500/40' }}"
                            title="Panduan Suara"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.757 3.63 8.25 4.51 8.25H6.75z" />
                            </svg>
                            <span>Audio</span>
                        </button>

                        <button 
                            type="button" 
                            wire:click="callOfficer" 
                            class="px-3 py-2 rounded-xl bg-[#F7941D] hover:bg-[#E08316] active:scale-95 text-slate-900 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer shadow-md"
                        >
                            <svg class="w-4 h-4 text-slate-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            <span>Bantuan</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        {{-- MAIN BODY WORKSPACE (MAX-W-7XL) --}}
        <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-6">
            {{-- 3 MODE OPERASIONAL SENTUH BESAR --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                {{-- TAB 1: AMBIL TIKET ANTREAN PST --}}
                <button 
                    type="button" 
                    wire:click="$set('activeTab', 'pst_ticket')"
                    class="p-4 rounded-2xl transition flex items-start gap-3.5 text-left cursor-pointer border {{ $activeTab === 'pst_ticket' ? 'bg-[#005288] text-white border-[#005288] shadow-lg shadow-sky-950/15 ring-2 ring-sky-300/50' : 'bg-white hover:bg-slate-50 text-slate-700 border-slate-200 shadow-xs' }}"
                >
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 {{ $activeTab === 'pst_ticket' ? 'bg-white/15 text-white' : 'bg-sky-50 text-[#005999]' }}">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-black tracking-tight {{ $activeTab === 'pst_ticket' ? 'text-white' : 'text-slate-900' }}">
                                AMBIL TIKET ANTREAN PST
                            </span>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $activeTab === 'pst_ticket' ? 'bg-[#FDE047] text-slate-950' : 'bg-amber-100 text-amber-800' }}">
                                4 PILAR
                            </span>
                        </div>
                        <p class="text-xs {{ $activeTab === 'pst_ticket' ? 'text-sky-100' : 'text-slate-500' }}">
                            Mahasiswa, Peneliti, Pimpinan, Publik Umum
                        </p>
                    </div>
                </button>

                {{-- TAB 2: BUKU TAMU KEDINASAN --}}
                <button 
                    type="button" 
                    wire:click="$set('activeTab', 'guestbook')"
                    class="p-4 rounded-2xl transition flex items-start gap-3.5 text-left cursor-pointer border {{ $activeTab === 'guestbook' ? 'bg-[#005288] text-white border-[#005288] shadow-lg shadow-sky-950/15 ring-2 ring-sky-300/50' : 'bg-white hover:bg-slate-50 text-slate-700 border-slate-200 shadow-xs' }}"
                >
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 {{ $activeTab === 'guestbook' ? 'bg-white/15 text-white' : 'bg-amber-50 text-amber-700' }}">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-black tracking-tight {{ $activeTab === 'guestbook' ? 'text-white' : 'text-slate-900' }}">
                                BUKU TAMU KEDINASAN
                            </span>
                            <span class="px-2 py-0.5 text-[10px] font-bold rounded-full {{ $activeTab === 'guestbook' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">
                                NON-PST
                            </span>
                        </div>
                        <p class="text-xs {{ $activeTab === 'guestbook' ? 'text-sky-100' : 'text-slate-500' }}">
                            Rapat, Audiensi, Koordinasi Antar-Sektor
                        </p>
                    </div>
                </button>

                {{-- TAB 3: CHECK-OUT KUNJUNGAN --}}
                <button 
                    type="button" 
                    wire:click="$set('activeTab', 'checkout')"
                    class="p-4 rounded-2xl transition flex items-start gap-3.5 text-left cursor-pointer border {{ $activeTab === 'checkout' ? 'bg-[#005288] text-white border-[#005288] shadow-lg shadow-sky-950/15 ring-2 ring-sky-300/50' : 'bg-white hover:bg-slate-50 text-slate-700 border-slate-200 shadow-xs' }}"
                >
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 {{ $activeTab === 'checkout' ? 'bg-white/15 text-white' : 'bg-emerald-50 text-emerald-700' }}">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-black tracking-tight {{ $activeTab === 'checkout' ? 'text-white' : 'text-slate-900' }}">
                                CHECK-OUT KUNJUNGAN
                            </span>
                        </div>
                        <p class="text-xs {{ $activeTab === 'checkout' ? 'text-sky-100' : 'text-slate-500' }}">
                            Selesai Layanan & Kembalikan Kartu Tamu
                        </p>
                    </div>
                </button>
            </div>

            {{-- NOTICE BANNER / PETUNJUK PENGAMBILAN TIKET --}}
            <div class="bg-sky-50/80 border border-sky-200 rounded-xl px-4 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-700 shadow-2xs">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-full bg-[#005999] text-white flex items-center justify-center font-bold text-xs shrink-0">
                        i
                    </div>
                    <p class="leading-relaxed">
                        <strong class="font-bold text-slate-900">Petunjuk Pengambilan Tiket:</strong> Sentuh salah satu kartu layanan di bawah. Mesin akan mencetak struk tiket antrian dan memanggil nomor Anda melalui layar monitor TV lobi.
                    </p>
                </div>
                <div class="flex items-center gap-1.5 shrink-0 text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Kertas Struk: Siap
                </div>
            </div>

            {{-- SEKSI 1: 4 PILAR LAYANAN TERPADU PST --}}
            <section class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-extrabold text-slate-900 tracking-tight">
                                Pilihan Layanan Pelayanan Statistik Terpadu (PST)
                            </h2>
                            <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded">
                                PILIH 1 LAYANAN
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Sentuh kartu layanan yang sesuai dengan keperluan konsultasi atau permohonan data Anda
                        </p>
                    </div>
                    <div class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-3 py-1 rounded-full shrink-0">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Seluruh Layanan GRATIS (Tanpa Pungutan)</span>
                    </div>
                </div>

                {{-- GRID 4 PILAR KARTU SENTUH --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- KARTU 1: PERPUSTAKAAN & RUANG BACA (KODE A) --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 text-[11px] font-extrabold rounded-md bg-slate-100 text-slate-700">
                                    LOKET 1
                                </span>
                                <span class="px-2.5 py-1 text-[11px] font-mono font-black rounded-md bg-sky-100 text-[#005999]">
                                    KODE A
                                </span>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-sky-50 text-[#005999] flex items-center justify-center mb-3">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mb-1.5">
                                Perpustakaan & Ruang Baca
                            </h3>
                            <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                Membaca dan meminjam publikasi statistik BPS terbaru, koleksi softcopy buku, & katalog sensus Jawa Tengah 2010 - sekarang.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ $this->queueCounts['A'] }} antrian (~0 mnt)
                            </span>
                            <button 
                                type="button" 
                                wire:click="issueTicket('A')" 
                                class="px-3 py-1.5 rounded-xl bg-[#005288] hover:bg-[#003e6b] active:scale-95 text-white font-extrabold text-xs transition flex items-center gap-1 cursor-pointer shadow-sm"
                            >
                                <span>AMBIL A</span>
                                <span>&gt;</span>
                            </button>
                        </div>
                    </div>

                    {{-- KARTU 2: KONSULTASI STATISTIK & DATA (KODE B) --}}
                    <div class="bg-white rounded-2xl border-2 border-[#F7941D]/30 shadow-sm hover:shadow-md transition p-5 flex flex-col justify-between relative">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-1.5">
                                    <span class="px-2 py-0.5 text-[10px] font-extrabold rounded bg-[#F7941D] text-slate-950">
                                        FAVORIT
                                    </span>
                                    <span class="text-[11px] font-bold text-slate-600">LOKET 2 & 3</span>
                                </div>
                                <span class="px-2.5 py-1 text-[11px] font-mono font-black rounded-md bg-amber-100 text-amber-900">
                                    KODE B
                                </span>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center mb-3">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mb-1.5">
                                Konsultasi Statistik & Data
                            </h3>
                            <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                Konsultasi metodologi, analisis indikator makro (inflasi, kemiskinan, PDRB), & permohonan data sektoral.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ $this->queueCounts['B'] }} antrian (~0 mnt)
                            </span>
                            <button 
                                type="button" 
                                wire:click="issueTicket('B')" 
                                class="px-3 py-1.5 rounded-xl bg-[#005288] hover:bg-[#003e6b] active:scale-95 text-white font-extrabold text-xs transition flex items-center gap-1 cursor-pointer shadow-sm"
                            >
                                <span>AMBIL B</span>
                                <span>&gt;</span>
                            </button>
                        </div>
                    </div>

                    {{-- KARTU 3: PEMBELIAN DATA MIKRO & PETA (KODE C) --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 text-[11px] font-extrabold rounded-md bg-slate-100 text-slate-700">
                                    LOKET 3
                                </span>
                                <span class="px-2.5 py-1 text-[11px] font-mono font-black rounded-md bg-sky-100 text-[#005999]">
                                    KODE C
                                </span>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center mb-3">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mb-1.5">
                                Pembelian Data Mikro & Peta
                            </h3>
                            <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                Pemesanan data mentah sensus/survei, peta digital wilayah kerja statistik, & publikasi berbayar PNBP resmi.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ $this->queueCounts['C'] }} antrian (~0 mnt)
                            </span>
                            <button 
                                type="button" 
                                wire:click="issueTicket('C')" 
                                class="px-3 py-1.5 rounded-xl bg-[#005288] hover:bg-[#003e6b] active:scale-95 text-white font-extrabold text-xs transition flex items-center gap-1 cursor-pointer shadow-sm"
                            >
                                <span>AMBIL C</span>
                                <span>&gt;</span>
                            </button>
                        </div>
                    </div>

                    {{-- KARTU 4: REKOMENDASI (ROMANIK) (KODE D) --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-100 text-emerald-800">
                                    SOP TERIKAT
                                </span>
                                <span class="px-2.5 py-1 text-[11px] font-mono font-black rounded-md bg-emerald-100 text-emerald-900">
                                    KODE D
                                </span>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-3">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mb-1.5">
                                Rekomendasi (ROMANIK)
                            </h3>
                            <p class="text-xs text-slate-500 leading-relaxed mb-4">
                                Permohonan rekomendasi kegiatan statistik sektoral bagi OPD Pemerintah Daerah Jawa Tengah sesuai Perpres Satu Data Indonesia.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ $this->queueCounts['D'] }} antrian (~0 mnt)
                            </span>
                            <button 
                                type="button" 
                                wire:click="issueTicket('D')" 
                                class="px-3 py-1.5 rounded-xl bg-[#6CBE45] hover:bg-[#5aa437] active:scale-95 text-white font-extrabold text-xs transition flex items-center gap-1 cursor-pointer shadow-sm"
                            >
                                <span>AMBIL D</span>
                                <span>&gt;</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            {{-- SEKSI 2: SIMULASI CETAK STRUK FISIK (THERMAL RECEIPT EPRINT TP-70XX) --}}
            <section class="space-y-3">
                <div class="flex items-center justify-between text-xs text-slate-600">
                    <div class="flex items-center gap-2 font-bold text-slate-800">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Simulasi Cetak Struk Fisik (Thermal Receipt EPRINT TP-70XX)</span>
                    </div>
                    <span class="text-slate-400 font-mono text-[11px]">Protokol Cetak ESC/POS Raw Serial &amp; Target ESC/P</span>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                    {{-- KOLOM KIRI (7 KOLOM): DISPLAY KARTU DARK NAVY --}}
                    <div class="lg:col-span-7 bg-[#0B1528] rounded-3xl p-6 sm:p-8 text-white flex flex-col justify-between shadow-xl shadow-slate-950/20 border border-slate-800">
                        <div>
                            @if($ticketTaken)
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-sky-950/80 text-sky-400 border border-sky-800/60 mb-4">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    <span>Tiket Telah Diambil Pengunjung</span>
                                </div>

                                <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white mb-2">
                                    Tiket Fisik Telah Diambil
                                </h2>
                                <p class="text-xs text-slate-300 leading-relaxed max-w-xl">
                                    Terima kasih atas kunjungan Anda. Silakan bawa struk fisik dan menuju ruang tunggu PST. Nomor antrean Anda akan dipanggil melalui layar monitor TV lobi.
                                </p>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-950/80 text-emerald-400 border border-emerald-800/60 mb-4">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    <span>Tiket Baru Berhasil Diterbitkan</span>
                                </div>

                                <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-white mb-2">
                                    Silakan Ambil Kertas Tiket Fisik Anda
                                </h2>
                                <p class="text-xs text-slate-300 leading-relaxed max-w-xl">
                                    Struk fisik telah tercetak otomatis melalui dispenser thermal printer di bawah layar kiosk. Nomor antrean juga panggilan suara merdu melalui layar TV lobi pilar display di ruang tunggu Anda.
                                </p>
                            @endif

                            {{-- 3 METRIC BOXES --}}
                            <div class="grid grid-cols-3 gap-3 my-6">
                                <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                                    <span class="text-[10px] font-bold tracking-wider text-slate-400 block mb-1">
                                        NOMOR TIKET
                                    </span>
                                    <span class="font-mono-numbers text-2xl sm:text-3xl font-black text-[#FDE047]">
                                        {{ $activeTicket->ticket_number ?? 'A-024' }}
                                    </span>
                                </div>

                                <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                                    <span class="text-[10px] font-bold tracking-wider text-slate-400 block mb-1">
                                        MEJA TUJUAN
                                    </span>
                                    <span class="text-xs sm:text-sm font-bold text-white block mt-1 leading-snug">
                                        @if($activeTicket)
                                            Meja {{ $activeTicket->counter?->counter_name ?? 'Loket PST' }}
                                        @else
                                            Meja Konsultasi 2 &amp; 3
                                        @endif
                                    </span>
                                </div>

                                <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                                    <span class="text-[10px] font-bold tracking-wider text-slate-400 block mb-1">
                                        ESTIMASI TUNGGU
                                    </span>
                                    <span class="text-xs sm:text-sm font-bold text-emerald-400 block mt-1">
                                        &lt; 10 ~ 15 Menit
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- TOMBOL AKSI CETAK ULANG / SELESAI --}}
                        <div class="flex items-center gap-3 pt-4 border-t border-white/10">
                            <button 
                                type="button" 
                                wire:click="reprintTicket" 
                                class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/15 active:scale-95 text-white text-xs font-bold transition flex items-center gap-2 cursor-pointer border border-white/15"
                            >
                                <svg class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.07-.367-2.184-.367-3.329 0-4.636 3.73-8.397 8.333-8.397.666 0 1.312.079 1.93.228M17.28 10.171c.24 1.07.367 2.184.367 3.329 0 4.636-3.73 8.397-8.333 8.397a8.47 8.47 0 01-1.93-.228m0 0a8.55 8.55 0 01-4.707-3.03M19.5 12a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />
                                </svg>
                                <span>CETAK ULANG STRUK</span>
                            </button>

                            @if($ticketTaken)
                                <button 
                                    type="button" 
                                    disabled 
                                    class="px-5 py-2.5 rounded-xl bg-slate-800 text-emerald-400 border border-slate-700 text-xs font-bold flex items-center gap-1.5 cursor-default shadow-xs"
                                >
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    <span>TIKET SUDAH DIAMBIL</span>
                                </button>
                            @else
                                <button 
                                    type="button" 
                                    wire:click="clearPrintedTicket" 
                                    class="px-5 py-2.5 rounded-xl bg-[#6CBE45] hover:bg-[#59a435] active:scale-95 text-white text-xs font-black transition flex items-center gap-1.5 cursor-pointer shadow-md"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    <span>SELESAI / AMBIL TIKET</span>
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- KOLOM KANAN (5 KOLOM): PRATINJAU STRUK TERMAL FISIK (80MM) --}}
                    <div class="lg:col-span-5 flex justify-center items-center">
                        <div 
                            id="printable-thermal-ticket"
                            class="w-full max-w-[320px] bg-white rounded-2xl shadow-xl border border-slate-200 p-6 text-center text-slate-800 receipt-tear-bottom"
                        >
                            {{-- HEADER STRUK --}}
                            <div class="border-b border-dashed border-slate-300 pb-3 mb-3">
                                <div class="w-7 h-7 mx-auto mb-1 text-[#005288]">
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                    </svg>
                                </div>
                                <h4 class="font-extrabold text-xs text-slate-900 tracking-tight">
                                    BPS PROVINSI JAWA TENGAH
                                </h4>
                                <p class="text-[10px] font-bold text-slate-600">
                                    PELAYANAN STATISTIK TERPADU (PST)
                                </p>
                                <p class="text-[9px] text-slate-400 font-mono mt-0.5">
                                    Jl. Pahlawan No. 6 Semarang - (024) 8413621
                                </p>
                            </div>

                            {{-- NOMOR TIKET JUMBO --}}
                            <div class="py-2 border-b border-dashed border-slate-300 mb-3">
                                <span class="text-[10px] font-bold tracking-wider text-slate-500 uppercase">
                                    NOMOR TIKET ANTRIAN
                                </span>
                                <div class="font-mono-numbers text-4xl sm:text-5xl font-black tracking-tight text-[#005288] my-1">
                                    {{ $activeTicket->ticket_number ?? 'A-024' }}
                                </div>
                                <div class="inline-block px-2.5 py-0.5 rounded text-[10px] font-black uppercase bg-amber-100 text-amber-900 mb-1">
                                    {{ $activeTicket ? strtoupper($activeTicket->service_type) : 'KONSULTASI & STATISTIK' }}
                                </div>
                                @if($ticketTaken)
                                    <div class="block">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200 mb-1">
                                            <svg class="w-2.5 h-2.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            Struk Fisik Sudah Diambil
                                        </span>
                                    </div>
                                @endif
                                <p class="text-xs font-bold text-slate-800">
                                    {{ $activeTicket->counter?->counter_name ?? 'MEJA LAYANAN 2 & 3' }}
                                </p>
                            </div>

                            {{-- META DETAILS --}}
                            <div class="space-y-1 text-[10px] font-mono text-slate-500 text-left border-b border-dashed border-slate-300 pb-3 mb-3">
                                <div class="flex justify-between">
                                    <span>Waktu Cetak:</span>
                                    <span class="font-bold text-slate-800">{{ now()->translatedFormat('d M Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Sisa Antrian:</span>
                                    <span class="font-bold text-emerald-600">0 Pengunjung</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Target Layanan:</span>
                                    <span class="font-bold text-slate-800">15 Menit (SLA)</span>
                                </div>
                            </div>

                            {{-- DYNAMIC QR CODE --}}
                            <div class="py-1 flex flex-col items-center justify-center">
                                <div class="w-28 h-28 p-1.5 bg-white border border-slate-200 rounded-lg shadow-2xs flex items-center justify-center mb-1.5">
                                    @if($qrCodeSvg)
                                        <div class="w-full h-full [&>svg]:w-full [&>svg]:h-full">
                                            {!! $qrCodeSvg !!}
                                        </div>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300 text-xs font-mono">
                                            [QR Code]
                                        </div>
                                    @endif
                                </div>
                                <span class="text-[9px] font-mono text-slate-400">
                                    Scan untuk lacak antrian &amp; survei kepuasan
                                </span>
                            </div>

                            {{-- FOOTER STRUK --}}
                            <div class="pt-2 text-[9px] text-slate-400 leading-tight">
                                <p>Terima kasih atas kunjungan Anda di PST BPS Jawa Tengah.</p>
                                <p class="font-semibold text-slate-600">Simpan tiket ini hingga nomor dipanggil.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- SEKSI 3: REGISTRASI CEPAT TAMU KEDINASAN BPS (BUKU TAMU DIGITAL) --}}
            <section class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
                {{-- BANNER HEADER REGISTRASI --}}
                <div class="bg-gradient-to-r from-[#005288] to-[#005999] p-5 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-0.5">
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded bg-amber-400 text-slate-950 uppercase">
                                    KUNJUNGAN NON-PST
                                </span>
                                <span class="text-xs text-sky-200 font-medium">Lobi Resepsionis</span>
                            </div>
                            <h3 class="text-base font-extrabold tracking-tight text-white">
                                Registrasi Cepat Tamu Kedinasan BPS Provinsi Jawa Tengah
                            </h3>
                        </div>
                    </div>

                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-950/80 text-emerald-300 border border-emerald-700/60 shrink-0">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>BUKU TAMU DIGITAL: Penjadwalan &amp; Check-In Mandiri</span>
                    </div>
                </div>

                {{-- FORMULIR BUKU TAMU --}}
                <form wire:submit.prevent="registerGuest" class="p-6 sm:p-8 space-y-5">
                    {{-- ROW 1: 4 KOLOM FIELD IDENTITAS --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- FIELD 1: NOMOR WHATSAPP (HP) --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                NOMOR WHATSAPP (HP)*
                            </label>
                            <div class="flex rounded-xl border @error('guestPhone') border-rose-300 bg-rose-50/20 @else border-slate-200 bg-slate-50/60 focus-within:bg-white focus-within:border-[#005288] focus-within:ring-2 focus-within:ring-sky-100 @enderror transition shadow-2xs overflow-hidden">
                                <span class="px-3 py-2 text-xs font-bold bg-slate-100 text-slate-500 border-r border-slate-200 flex items-center">
                                    +62
                                </span>
                                <input 
                                    type="text" 
                                    wire:model.live.debounce.400ms="guestPhone"
                                    placeholder="" 
                                    class="w-full px-3 py-2 text-xs font-mono text-slate-800 placeholder-slate-400 bg-transparent focus:outline-none"
                                />
                            </div>
                            @if($isKnownVisitor)
                                <p class="text-[10px] text-emerald-600 font-bold mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>✓ Profil Terverifikasi (Auto-Lookup)</span>
                                </p>
                            @else
                                <p class="text-[10px] text-slate-400 mt-1">✓ Terverifikasi WhatsApp</p>
                            @endif
                            @error('guestPhone') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- FIELD 2: NIK / NOMOR KTP --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                NIK / NOMOR KTP
                            </label>
                            <input 
                                type="text" 
                                wire:model="guestNik"
                                placeholder="" 
                                maxlength="16"
                                class="w-full px-3.5 py-2 text-xs font-mono text-slate-800 placeholder-slate-400 rounded-xl border border-slate-200 bg-slate-50/60 focus:bg-white focus:border-[#005288] focus:ring-2 focus:ring-sky-100 transition shadow-2xs focus:outline-none"
                            />
                            <p class="text-[10px] text-slate-400 mt-1">Basis Otomatis Auto-Lookup</p>
                        </div>

                        {{-- FIELD 3: NAMA LENGKAP TAMU --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                NAMA LENGKAP TAMU*
                            </label>
                            <input 
                                type="text" 
                                wire:model="guestName"
                                placeholder="" 
                                class="w-full px-3.5 py-2 text-xs font-medium text-slate-800 placeholder-slate-400 rounded-xl border @error('guestName') border-rose-300 bg-rose-50/20 @else border-slate-200 bg-slate-50/60 focus:bg-white focus:border-[#005288] focus:ring-2 focus:ring-sky-100 @enderror transition shadow-2xs focus:outline-none"
                            />
                            <p class="text-[10px] text-slate-400 mt-1">Sesuai KTP Tamu</p>
                            @error('guestName') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- FIELD 4: ASAL INSTANSI / DINAS --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                ASAL INSTANSI / DINAS*
                            </label>
                            <input 
                                type="text" 
                                wire:model="guestInstitution"
                                placeholder="" 
                                class="w-full px-3.5 py-2 text-xs font-medium text-slate-800 placeholder-slate-400 rounded-xl border @error('guestInstitution') border-rose-300 bg-rose-50/20 @else border-slate-200 bg-slate-50/60 focus:bg-white focus:border-[#005288] focus:ring-2 focus:ring-sky-100 @enderror transition shadow-2xs focus:outline-none"
                            />
                            <p class="text-[10px] text-slate-400 mt-1">Organisasi Perangkat Daerah</p>
                            @error('guestInstitution') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- QUICK-FILL INSTANSI CHIPS --}}
                    <div class="flex items-center gap-2 flex-wrap text-xs">
                        <span class="text-slate-400 text-[11px] font-semibold">Pilihan Cepat Instansi Terdaftar:</span>
                        <button type="button" wire:click="quickFillInstitution('Dinas Kominfo Jateng')" class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-sky-50 hover:text-[#005288] hover:border-sky-300 border border-slate-200 text-slate-600 text-[11px] font-medium transition cursor-pointer">
                            Dinas Kominfo Jateng
                        </button>
                        <button type="button" wire:click="quickFillInstitution('Bappeda Prov Jateng')" class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-sky-50 hover:text-[#005288] hover:border-sky-300 border border-slate-200 text-slate-600 text-[11px] font-medium transition cursor-pointer">
                            Bappeda Prov Jateng
                        </button>
                        <button type="button" wire:click="quickFillInstitution('Inspektorat Jateng')" class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-sky-50 hover:text-[#005288] hover:border-sky-300 border border-slate-200 text-slate-600 text-[11px] font-medium transition cursor-pointer">
                            Inspektorat Jateng
                        </button>
                        <button type="button" wire:click="quickFillInstitution('Perguruan Tinggi (Undip / Unnes)')" class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-sky-50 hover:text-[#005288] hover:border-sky-300 border border-slate-200 text-slate-600 text-[11px] font-medium transition cursor-pointer">
                            Perguruan Tinggi (Undip / Unnes)
                        </button>
                        <button type="button" wire:click="quickFillInstitution('Media Massa / Wartawan')" class="px-2.5 py-1 rounded-full bg-slate-100 hover:bg-sky-50 hover:text-[#005288] hover:border-sky-300 border border-slate-200 text-slate-600 text-[11px] font-medium transition cursor-pointer">
                            Media Massa / Wartawan
                        </button>
                    </div>

                    {{-- ROW 2: 2 DROPDOWN TUJUAN & KEPERLUAN --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                KONTAK / TIM KERJA YANG DITUJU*
                            </label>
                            <select 
                                wire:model="guestTargetUnit"
                                class="w-full px-3.5 py-2.5 text-xs text-slate-800 rounded-xl border @error('guestTargetUnit') border-rose-300 bg-rose-50/20 @else border-slate-200 bg-slate-50/60 focus:bg-white focus:border-[#005288] focus:ring-2 focus:ring-sky-100 @enderror transition shadow-2xs focus:outline-none"
                            >
                                <option value="">-- Pilih Kontak / Tim Kerja yang Dituju --</option>
                                <option value="Tim IPDS (Integrasi Pengolahan &amp; Diseminasi Statistik)">Tim IPDS (Integrasi Pengolahan &amp; Diseminasi Statistik)</option>
                                <option value="Bagian Umum &amp; Kepegawaian">Bagian Umum &amp; Kepegawaian</option>
                                <option value="Tim Statistik Sosial (Kesejahteraan Rakyat)">Tim Statistik Sosial (Kesejahteraan Rakyat)</option>
                                <option value="Tim Statistik Produksi (Pertanian, Industri, Tambang)">Tim Statistik Produksi (Pertanian, Industri, Tambang)</option>
                                <option value="Tim Statistik Distribusi (Inflasi, Perdagangan, Transportasi)">Tim Statistik Distribusi (Inflasi, Perdagangan, Transportasi)</option>
                                <option value="Tim Neraca Wilayah &amp; Analisis Statistik">Tim Neraca Wilayah &amp; Analisis Statistik</option>
                                <option value="Pimpinan / Kepala BPS Provinsi Jawa Tengah">Pimpinan / Kepala BPS Provinsi Jawa Tengah</option>
                            </select>
                            @error('guestTargetUnit') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                KEPERLUAN KUNJUNGAN KEDINASAN*
                            </label>
                            <select 
                                wire:model="guestPurpose"
                                class="w-full px-3.5 py-2.5 text-xs text-slate-800 rounded-xl border @error('guestPurpose') border-rose-300 bg-rose-50/20 @else border-slate-200 bg-slate-50/60 focus:bg-white focus:border-[#005288] focus:ring-2 focus:ring-sky-100 @enderror transition shadow-2xs focus:outline-none"
                            >
                                <option value="">-- Pilih Keperluan Kunjungan Kedinasan --</option>
                                <option value="Koordinasi Satu Data Indonesia &amp; Interoperabilitas API Statistik">Koordinasi Satu Data Indonesia &amp; Interoperabilitas API Statistik</option>
                                <option value="Audiensi &amp; Konsultasi Teknis Sektoral">Audiensi &amp; Konsultasi Teknis Sektoral</option>
                                <option value="Rapat Koordinasi Tim Kerja Antar-Instansi">Rapat Koordinasi Tim Kerja Antar-Instansi</option>
                                <option value="Pengantaran Dokumen Dinas / Surat Keputusan">Pengantaran Dokumen Dinas / Surat Keputusan</option>
                                <option value="Wawancara Penelitian / Studi Lapangan">Wawancara Penelitian / Studi Lapangan</option>
                            </select>
                            @error('guestPurpose') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- SUBMIT BARIS BAWAH --}}
                    <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-2 text-xs text-slate-500">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Pembatasan Akses Area Kerja tertib Standar ISO 27001 BPS Provinsi Jawa Tengah</span>
                        </div>

                        <button 
                            type="submit" 
                            class="px-6 py-3 rounded-xl bg-[#005288] hover:bg-[#003e6b] active:scale-95 text-white font-extrabold text-xs transition flex items-center justify-center gap-2 cursor-pointer shadow-md"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.948c0-.621-.504-1.125-1.125-1.125H5.625c-.621 0-1.125.504-1.125 1.125v12.375c0 .621.504 1.125 1.125 1.125h2.25" />
                            </svg>
                            <span>Simpan Buku Tamu &amp; Terbitkan Visitor Pass</span>
                        </button>
                    </div>
                </form>
            </section>
        </main>
    </div>

    {{-- FOOTER KIOSK LOBI (DARK NAVY FOOTER WITH HARDWARE STATUS) --}}
    <footer class="w-full bg-[#0B132B] text-white border-t border-slate-800 px-6 py-4 mt-8">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row md:items-center justify-between gap-4 text-xs">
            {{-- SISI KIRI: KEBIJAKAN ZERO-RUPIAH & STATUS HARDWARE --}}
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 text-[10px] font-black rounded bg-[#F7941D] text-slate-950 uppercase">
                        KEBIJAKAN ZERO-RUPIAH
                    </span>
                    <p class="text-[11px] text-slate-300">
                        Sesuai PP Nomor 28 Tahun 2024 tentang PNBP BPS RI dan Maklumat Pelayanan PST Bebas Gratifikasi. Seluruh layanan konsultasi dan rekomendasi statistik tidak dipungut biaya apapun (Rp 0,-).
                    </p>
                </div>

                {{-- STATUS INDIKATOR HARDWARE KIOSK --}}
                <div class="flex items-center gap-4 text-[11px] font-mono text-slate-400">
                    <span class="inline-flex items-center gap-1.5 text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Status Kertas: Aman (80/80mm)
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Koneksi LAN: 1 Gbps (Laten 2ms)
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Scanner KTP: Aktif &amp; Siap
                    </span>
                    <span class="inline-flex items-center gap-1.5 text-sky-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                        Dispenser Card: Ready
                    </span>
                </div>
            </div>

            {{-- SISI KANAN: ID TERMINAL & TOMBOL PANGGIL PETUGAS LOBI --}}
            <div class="flex items-center gap-4 shrink-0">
                <div class="text-right text-[11px] font-mono text-slate-400">
                    <div class="text-white font-bold">ID TERMINAL: KIOSK_LOBBY_01</div>
                    <div>PRISTELA OS KIOSK HARDWARE v2.4</div>
                </div>

                <button 
                    type="button" 
                    wire:click="callOfficer" 
                    class="px-4 py-2.5 rounded-xl bg-[#F7941D] hover:bg-[#E08316] active:scale-95 text-slate-950 font-black text-xs transition flex items-center gap-1.5 cursor-pointer shadow-md"
                >
                    <svg class="w-4 h-4 text-slate-950" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span>Panggil Petugas Lobi</span>
                </button>
            </div>
        </div>
    </footer>

    {{-- MODAL 1: CHECK-OUT KUNJUNGAN DRAWER / MODAL --}}
    @if($activeTab === 'checkout')
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 text-left relative animate-in fade-in zoom-in-95 duration-200">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Check-Out Kunjungan Lobi BPS</h3>
                            <p class="text-xs text-slate-500">Pilih nama Anda untuk menyelesaikan kunjungan dan mengembalikan kartu tamu.</p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('activeTab', 'pst_ticket')" class="text-slate-400 hover:text-slate-600 p-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- SEARCH FIELD --}}
                <div class="my-4">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="searchCheckout" 
                        placeholder="Ketik Nama Tamu atau Nomor HP / WhatsApp..." 
                        class="w-full px-4 py-2.5 text-xs text-slate-800 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#005288] focus:outline-none"
                    />
                </div>

                {{-- DAFTAR TAMU AKTIF HARI INI --}}
                <div class="max-h-72 overflow-y-auto space-y-2 pr-1">
                    @forelse($this->activeVisitors as $log)
                        <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-white flex items-center justify-between gap-3 transition">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">{{ $log->visitor->name }}</h4>
                                <p class="text-[11px] text-slate-500">{{ $log->visitor->institution ?? 'Umum / Kedinasan' }} &bull; Check-in: {{ $log->check_in_at->format('H:i') }} WIB</p>
                            </div>
                            <button 
                                type="button" 
                                wire:click="checkoutVisitor({{ $log->id }})" 
                                class="px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1 cursor-pointer"
                            >
                                <span>Check-Out</span>
                            </button>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400">
                            Tidak ada data tamu aktif yang sedang berkunjung saat ini.
                        </div>
                    @endforelse
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 text-right">
                    <button type="button" wire:click="$set('activeTab', 'pst_ticket')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL 2: BANTUAN PETUGAS LOBI DIPANGGIL --}}
    @if($showHelpModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 text-center shadow-2xl border border-slate-200 relative animate-in fade-in zoom-in-95 duration-200">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900 mb-1">Petugas Lobi Telah Dihubungi</h3>
                <p class="text-xs text-slate-600 leading-relaxed mb-5">
                    Sinyal bantuan telah dikirim ke konsol meja operator. Petugas Pelayanan Statistik Terpadu sedang menuju ke anjungan Kios Lobi 01 untuk mendampingi Anda.
                </p>
                <button 
                    type="button" 
                    wire:click="$set('showHelpModal', false)" 
                    class="w-full py-2.5 rounded-xl bg-[#005288] hover:bg-[#003e6b] text-white text-xs font-bold transition shadow-sm cursor-pointer"
                >
                    Baik, Terima Kasih
                </button>
            </div>
        </div>
    @endif

    {{-- MODAL 3: VISITOR PASS BERHASIL DITERBITKAN --}}
    @if($showSuccessPassModal && $activeVisitorPass)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-3xl max-w-sm w-full p-6 text-center shadow-2xl border border-slate-200 relative animate-in fade-in zoom-in-95 duration-200">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900 mb-0.5">Visitor Pass Diterbitkan</h3>
                <p class="text-xs text-slate-500 mb-4">Silakan tunjukkan kartu akses ini ke petugas lobi kedinasan.</p>

                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/90 text-left space-y-2 font-mono text-xs mb-5">
                    <div class="flex justify-between border-b border-slate-200 pb-2">
                        <span class="text-slate-500 font-sans">KODE AKSES:</span>
                        <span class="font-bold text-[#005288]">{{ $activeVisitorPass['pass_code'] }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[10px] block font-sans">NAMA TAMU:</span>
                        <span class="font-bold text-slate-900">{{ $activeVisitorPass['name'] }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[10px] block font-sans">INSTANSI:</span>
                        <span class="text-slate-700">{{ $activeVisitorPass['institution'] }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 text-[10px] block font-sans">TUJUAN:</span>
                        <span class="text-slate-700">{{ $activeVisitorPass['target'] }}</span>
                    </div>
                </div>

                <button 
                    type="button" 
                    wire:click="$set('showSuccessPassModal', false)" 
                    class="w-full py-2.5 rounded-xl bg-[#005288] hover:bg-[#003e6b] text-white text-xs font-bold transition shadow-sm cursor-pointer"
                >
                    Selesai &amp; Tutup
                </button>
            </div>
        </div>
    @endif
</div>
