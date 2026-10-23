<div 
    x-data="{
        currentTime: '',
        currentDate: '',
        audioEnabled: false,
        isFullscreen: false,
        isCalling: false,
        activeTicketNumber: @js($this->getDisplayQueueCode($activeTicket)),
        activeCounterName: @js($activeTicket?->counter?->counter_name ?? 'Loket 1 - Konsultasi Statistik'),
        
        init() {
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
            
            // Periksa kesiapan suara TTS browser
            if ('speechSynthesis' in window) {
                window.speechSynthesis.onvoiceschanged = () => {
                    window.speechSynthesis.getVoices();
                };
            }
        },

        updateClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            this.currentDate = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
            
            const h = String(now.getHours()).padStart(2, '0');
            const m = String(now.getMinutes()).padStart(2, '0');
            const s = String(now.getSeconds()).padStart(2, '0');
            this.currentTime = `${h}:${m}:${s} WIB`;
        },

        enableAudio() {
            this.audioEnabled = true;
            this.playChime();
        },

        toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().then(() => {
                    this.isFullscreen = true;
                }).catch(() => {});
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen().then(() => {
                        this.isFullscreen = false;
                    }).catch(() => {});
                }
            }
        },

        playChime() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const ctx = new AudioCtx();
                if (ctx.state === 'suspended') {
                    ctx.resume();
                }

                const playTone = (freq, start, duration) => {
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, ctx.currentTime + start);
                    gain.gain.setValueAtTime(0.25, ctx.currentTime + start);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + start + duration);
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.start(ctx.currentTime + start);
                    osc.stop(ctx.currentTime + start + duration);
                };

                // Tri-tone harmonious chime: C5 (523.25Hz) -> E5 (659.25Hz) -> G5 (783.99Hz)
                playTone(523.25, 0.0, 0.35);
                playTone(659.25, 0.22, 0.35);
                playTone(783.99, 0.44, 0.55);
            } catch (e) {
                console.warn('Audio chime warning:', e);
            }
        },

        speakCall(ticketText, counterText) {
            if (!('speechSynthesis' in window)) return;
            
            try {
                window.speechSynthesis.cancel();
                
                const safeTicket = String(ticketText || this.activeTicketNumber || 'A-001');
                const safeCounter = String(counterText || this.activeCounterName || 'Loket 1 - Konsultasi Statistik');
                
                let spokenTicket = safeTicket;
                const match = safeTicket.match(/([A-Za-z])-?0*(\d+)/);
                if (match) {
                    spokenTicket = `${match[1]} ${parseInt(match[2], 10)}`;
                }

                const sentence = `Nomor antrean, ${spokenTicket}, silakan menuju, ${safeCounter}`;
                const utterance = new SpeechSynthesisUtterance(sentence);
                utterance.lang = 'id-ID';
                utterance.rate = 0.88;
                utterance.pitch = 1.05;

                const voices = window.speechSynthesis.getVoices();
                const idVoice = voices.find(v => v.lang.startsWith('id') || v.lang.includes('Indonesia'));
                if (idVoice) {
                    utterance.voice = idVoice;
                }

                window.speechSynthesis.speak(utterance);
            } catch (err) {
                console.warn('Speech synthesis warning:', err);
            }
        },

        handleCallEvent(eventData) {
            const detail = (Array.isArray(eventData) ? eventData[0] : eventData) || {};
            const ticketCode = detail.ticketCode || detail.ticketNumber || this.activeTicketNumber || 'A-001';
            const counterName = detail.counterName || this.activeCounterName || 'Loket 1 - Konsultasi Statistik';

            this.activeTicketNumber = ticketCode;
            this.activeCounterName = counterName;
            this.isCalling = true;
            
            if (this.audioEnabled) {
                this.playChime();
                setTimeout(() => {
                    this.speakCall(ticketCode, counterName);
                }, 850);
            }

            setTimeout(() => {
                this.isCalling = false;
            }, 6000);
        }
    }"
    x-on:queue-called.window="handleCallEvent($event.detail)"
    wire:poll.3s="refreshQueue"
    class="min-h-screen lg:h-screen w-screen flex flex-col justify-between bg-[#F8FAFC] text-slate-800 overflow-y-auto lg:overflow-hidden p-2.5 sm:p-3.5 select-none antialiased"
>
    <!-- 1. ZERO-SCROLL HEADER DENGAN TEMA BPS RESMI -->
    <header class="h-14 flex-shrink-0 bg-[#005288] text-white border-b-4 border-[#F7941D] rounded-2xl px-4 flex items-center justify-between gap-3 shadow-sm">
        <!-- Seksi Kiri: Logo BPS & Identitas Instansi -->
        <div class="flex items-center gap-3 flex-shrink-0">
            <div class="w-9 h-9 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center p-1.5 shadow-inner">
                <svg viewBox="0 0 24 24" fill="none" class="w-full h-full text-[#FDE047]" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-base sm:text-lg font-black tracking-tight text-white font-serif-bps leading-none">PRISTELA</h1>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        LIVE SINKRON
                    </span>
                </div>
                <p class="text-[10px] text-sky-100 font-medium">Pelayanan Statistik Terpadu - BPS Provinsi Jawa Tengah</p>
            </div>
        </div>

        <!-- Seksi Tengah: Running Marquee Maklumat Kedinasan BPS Bebas Biaya -->
        <div class="flex-1 min-w-0 max-w-xl xl:max-w-2xl hidden md:flex items-center bg-white/10 border border-white/15 rounded-xl px-3 py-1 overflow-hidden [mask-image:linear-gradient(to_right,transparent,black_16px,black_calc(100%-16px),transparent)]">
            <div class="w-full overflow-hidden relative">
                <div class="animate-marquee text-xs font-medium text-sky-100">
                    <span class="text-[#FDE047] font-bold">Maklumat Pelayanan:</span> Seluruh layanan PST BPS Provinsi Jawa Tengah <span class="text-emerald-300 font-bold">BEBAS BIAYA (Rp 0,-)</span>. Tolak gratifikasi dalam bentuk apa pun. Jam Pelayanan: Senin - Kamis 08.00 - 15.30 WIB, Jumat 08.00 - 15.00 WIB. Saluran Pengaduan Resmi: WhatsApp 0811-270-5001 atau lapor.go.id.
                </div>
            </div>
        </div>

        <!-- Seksi Kanan: Jam Digital Presisi & Tombol Audio -->
        <div class="flex items-center gap-3 flex-shrink-0">
            <div class="text-right">
                <div class="text-base sm:text-lg font-bold font-mono-numbers text-[#FDE047] tracking-tight leading-none" x-text="currentTime">08:00:00 WIB</div>
                <div class="text-[10px] text-sky-200 font-medium mt-0.5" x-text="currentDate">Senin, 19 Oktober 2026</div>
            </div>

            <!-- Tombol Pengaturan Audio & Layar Penuh -->
            <div class="flex items-center gap-1.5">
                <button 
                    type="button"
                    x-on:click="enableAudio()"
                    class="px-2.5 py-1 rounded-xl text-xs font-bold transition flex items-center gap-1 border cursor-pointer"
                    :class="audioEnabled ? 'bg-emerald-500/20 text-emerald-300 border-emerald-400/40' : 'bg-amber-500/20 text-[#FDE047] border-amber-400/40 animate-pulse'"
                >
                    <span x-show="audioEnabled">🔊 Suara Aktif</span>
                    <span x-show="!audioEnabled">🔇 Aktifkan Suara</span>
                </button>

                <button 
                    type="button"
                    x-on:click="toggleFullscreen()"
                    class="p-1 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white border border-white/15 transition cursor-pointer"
                    title="Beralih Layar Penuh"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- 2. KONTEN UTAMA: LAYAR BESAR VIDEO (KIRI) & SPOTLIGHT PANGGILAN ANTREAN (KANAN) -->
    <main class="flex-1 min-h-0 grid grid-cols-12 gap-3 my-2">
        <!-- KOLOM KIRI: LAYAR BESAR VIDEO EDUKASI STATISTIK (col-span-12 lg:col-span-7) -->
        <div class="col-span-12 lg:col-span-7 flex flex-col min-h-0 bg-white border-2 border-slate-200/90 rounded-2xl overflow-hidden shadow-sm">
            <!-- Header Frame Video -->
            <div class="bg-gradient-to-r from-slate-900 to-[#005288] px-3.5 py-1.5 flex items-center justify-between text-xs text-white flex-shrink-0">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    <span class="font-bold tracking-wide text-xs">Kanal Edukasi Statistik BPS Provinsi Jawa Tengah</span>
                </div>
                <span class="text-[10px] text-sky-200 font-medium">Informasi & Sensus Nasional</span>
            </div>

            <!-- Area Pemutar Video Utama -->
            <div class="flex-1 min-h-0 relative bg-black flex items-center justify-center overflow-hidden">
                <iframe 
                    class="w-full h-full border-0"
                    src="https://www.youtube-nocookie.com/embed/M7lc1UVf-VE?autoplay=1&mute=1&loop=1&playlist=M7lc1UVf-VE&controls=1&modestbranding=1&rel=0" 
                    title="Kanal Edukasi Statistik BPS Provinsi Jawa Tengah"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen
                ></iframe>
            </div>

            <!-- Bar Informasi Bawah Video -->
            <div class="bg-slate-50 border-t border-slate-200 px-3 py-1.5 flex items-center justify-between text-xs text-slate-600 flex-shrink-0">
                <div class="flex items-center gap-2 truncate">
                    <span class="px-2 py-0.5 rounded text-[9px] font-extrabold bg-[#005999] text-white uppercase tracking-wider flex-shrink-0">
                        Portal Data Terpadu
                    </span>
                    <span class="truncate font-medium text-slate-700 text-xs">
                        Unduh publikasi berkala, data inflasi bulanan, PDRB, dan indikator kemiskinan di portal resmi <strong class="text-[#005288]">jateng.bps.go.id</strong>
                    </span>
                </div>
                <span class="text-[10px] font-mono-numbers text-slate-400 flex-shrink-0 hidden sm:inline">
                    BPS Jawa Tengah
                </span>
            </div>
        </div>

        <!-- KOLOM KANAN: SPOTLIGHT PANGGILAN ANTREAN JUMBO (col-span-12 lg:col-span-5) -->
        <div class="col-span-12 lg:col-span-5 flex flex-col min-h-0">
            <div 
                class="h-full bg-white border-2 rounded-2xl p-3 sm:p-4 flex flex-col justify-between transition-all duration-300 relative shadow-sm overflow-hidden"
                :class="isCalling ? 'border-[#F7941D] shadow-[0_0_25px_rgba(247,148,29,0.25)] ring-2 ring-[#F7941D]/30' : 'border-slate-200/90'"
            >
                <!-- Lencana Status Panggilan -->
                <div class="flex items-center justify-between flex-shrink-0">
                    <span 
                        class="px-2.5 py-0.5 rounded-lg text-[11px] font-black tracking-wider uppercase flex items-center gap-1.5 border"
                        :class="isCalling || '{{ $activeTicket?->status }}' === 'calling' 
                            ? 'bg-amber-50 text-[#F7941D] border-[#F7941D]/40 animate-pulse' 
                            : 'bg-emerald-50 text-emerald-700 border-emerald-300'"
                    >
                        <span class="w-1.5 h-1.5 rounded-full" :class="isCalling ? 'bg-[#F7941D] animate-ping' : 'bg-emerald-500'"></span>
                        {{ ($activeTicket?->status === 'calling') ? 'SEDANG DIPANGGIL' : 'SEDANG DILAYANI' }}
                    </span>

                    <span class="text-[11px] text-slate-500 font-mono-numbers bg-slate-100 px-2 py-0.5 rounded-md">
                        Tiket: {{ $activeTicket?->service_type ?? 'Konsultasi Statistik' }}
                    </span>
                </div>

                <!-- Nomor Tiket Jumbo -->
                <div class="my-auto py-1 text-center flex-shrink-0">
                    <div class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-0.5">Nomor Antrean</div>
                    <div 
                        class="text-5xl sm:text-6xl xl:text-7xl font-black font-mono-numbers tracking-tight text-[#F7941D] drop-shadow-sm leading-none py-0.5"
                        x-text="activeTicketNumber"
                    >
                        {{ $this->getDisplayQueueCode($activeTicket) }}
                    </div>

                    <!-- Token Serial & Nama Tamu -->
                    <div class="flex items-center justify-center flex-wrap gap-1.5 mt-1">
                        <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[11px] font-mono-numbers border border-slate-200">
                            Token: {{ $activeTicket?->ticket_number ?? 'A-001' }}
                        </span>
                        @if($activeTicket?->visitor_name)
                            <span class="text-[11px] font-bold text-slate-700">
                                • {{ $activeTicket->visitor_name }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Penanda Meja Tujuan (Highlight Banner) -->
                <div class="bg-gradient-to-r from-[#005288] to-[#005999] rounded-xl p-2.5 sm:p-3 text-white shadow-sm border border-blue-400/20 flex-shrink-0">
                    <div class="text-[10px] font-bold text-sky-200 uppercase tracking-wider">Silakan Menuju</div>
                    <div class="text-lg sm:text-xl xl:text-2xl font-black text-white tracking-wide mt-0.5 leading-snug" x-text="activeCounterName">
                        {{ $activeTicket?->counter?->counter_name ?? 'Loket 1 - Konsultasi Statistik' }}
                    </div>
                </div>

                <!-- Kartu Identitas Petugas Jaga & Demo Uji Panggil -->
                <div class="mt-2 bg-slate-50 border border-slate-200 rounded-xl p-2 sm:p-2.5 flex items-center justify-between gap-2 flex-shrink-0">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-xl bg-[#005288] text-white font-extrabold flex items-center justify-center text-xs shadow-xs flex-shrink-0">
                            {{ substr($activeTicket?->servedBy?->name ?? 'OP', 0, 2) }}
                        </div>
                        <div class="min-w-0">
                            <div class="text-xs font-bold text-slate-800 truncate">
                                {{ $activeTicket?->servedBy?->name ?? 'Petugas Meja PST' }}
                            </div>
                            <div class="text-[10px] text-slate-500 font-mono-numbers truncate">
                                NIP: {{ $activeTicket?->servedBy?->nip_baru ?? '-' }}
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Uji Panggilan (Demo / Test Chime) -->
                    <button 
                        type="button"
                        wire:click="triggerTestCall(1)"
                        x-on:click="enableAudio()"
                        class="px-2.5 py-1 rounded-lg text-xs font-bold bg-white hover:bg-slate-100 text-slate-700 border border-slate-300 shadow-2xs transition cursor-pointer flex-shrink-0"
                        title="Klik untuk menguji simulasi audio panggilan"
                    >
                        Uji Panggil
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- 3. MATRIKS MEJA LAYANAN PST (5 LOKET) - DIBUAT RAPI PROPORSIONAL & BEBAS OVERLAP -->
    <section class="flex-shrink-0 bg-white border-2 border-slate-200/90 rounded-2xl p-2.5 sm:p-3 shadow-sm relative z-10">
        <div class="flex items-center justify-between pb-1.5 border-b border-slate-200">
            <h3 class="text-xs sm:text-sm font-black uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#F7941D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Matriks Meja Layanan PST (5 Loket)
            </h3>
            <div class="flex items-center gap-3 text-[10px] sm:text-[11px] font-medium text-slate-500">
                <span>Pembaruan Otomatis 3 Detik</span>
                <span class="text-slate-300">•</span>
                <span class="font-bold text-[#005288]">Zona Integritas WBK / WBBM</span>
            </div>
        </div>

        <!-- Daftar 5 Loket Diperbesar Berjejer 5 Kolom Proporsional -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 sm:gap-2.5 pt-2">
            @foreach($counters as $counter)
                @php
                    $currentTicket = $counter->getAttribute('current_ticket');
                    $isOpen = $counter->is_open;
                    $isActiveServing = $currentTicket && in_array($currentTicket->status, ['calling', 'serving']);
                @endphp
                <div 
                    class="border-2 rounded-xl p-2 sm:p-2.5 flex flex-col justify-between transition-all duration-200 shadow-2xs {{ $isActiveServing ? 'bg-amber-50/40 border-[#F7941D] ring-2 ring-[#F7941D]/20' : ($isOpen ? 'bg-slate-50/70 border-slate-200 hover:border-slate-300' : 'bg-slate-100/60 border-slate-200/80 opacity-75') }}"
                >
                    <!-- Header Kartu Loket -->
                    <div class="flex items-center justify-between pb-1 border-b border-slate-200/70">
                        <span class="text-xs sm:text-sm font-black text-slate-800">
                            Loket {{ $counter->counter_number }}
                        </span>
                        <span 
                            class="px-1.5 py-0.5 rounded-full text-[9px] font-black uppercase {{ $isOpen ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-slate-200 text-slate-500' }}"
                        >
                            {{ $isOpen ? 'Buka' : 'Tutup' }}
                        </span>
                    </div>

                    <!-- Nomor Tiket yang Dilayani Ukuran Besar -->
                    <div class="my-1 sm:my-1.5 text-center">
                        <div class="text-[9px] text-slate-400 uppercase tracking-wider font-bold">Tiket Aktif</div>
                        <div class="text-xl sm:text-2xl xl:text-3xl font-black font-mono-numbers mt-0.5 leading-none">
                            @if($currentTicket)
                                <span class="text-[#F7941D]">{{ $this->getDisplayQueueCode($currentTicket) }}</span>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </div>
                    </div>

                    <!-- Petugas Jaga -->
                    <div class="pt-1 border-t border-slate-200/70 text-[10px]">
                        <div class="text-slate-400 text-[9px] font-medium">Petugas:</div>
                        <div class="font-bold text-slate-700 truncate" title="{{ $counter->activeOperator?->name ?? 'Belum Ditugaskan' }}">
                            {{ $counter->activeOperator?->name ?? 'Belum Ada' }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- 4. FOOTER: 4 KARTU METRIK KPI ANTREAN HARIAN (48px) -->
    <footer class="h-12 flex-shrink-0 grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-2.5 mt-2">
        <!-- Kartu 1: Total Antrean Hari Ini -->
        <div class="bg-white border-2 border-slate-200/90 rounded-xl px-3 py-1.5 flex items-center justify-between shadow-2xs">
            <div>
                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-tight">Total Antrean Hari Ini</div>
                <div class="text-base sm:text-lg font-black font-mono-numbers text-slate-800 leading-tight">{{ $stats['total'] }}</div>
            </div>
            <div class="w-7 h-7 rounded-lg bg-blue-50 text-[#005288] border border-blue-200 flex items-center justify-center font-bold text-xs flex-shrink-0">
                Σ
            </div>
        </div>

        <!-- Kartu 2: Sedang Dilayani -->
        <div class="bg-white border-2 border-slate-200/90 rounded-xl px-3 py-1.5 flex items-center justify-between shadow-2xs">
            <div>
                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-tight">Sedang Dilayani</div>
                <div class="text-base sm:text-lg font-black font-mono-numbers text-[#F7941D] leading-tight">{{ $stats['serving'] }}</div>
            </div>
            <div class="w-7 h-7 rounded-lg bg-amber-50 text-[#F7941D] border border-amber-200 flex items-center justify-center font-bold text-xs flex-shrink-0">
                ⚡
            </div>
        </div>

        <!-- Kartu 3: Menunggu Panggilan -->
        <div class="bg-white border-2 border-slate-200/90 rounded-xl px-3 py-1.5 flex items-center justify-between shadow-2xs">
            <div>
                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-tight">Menunggu Panggilan</div>
                <div class="text-base sm:text-lg font-black font-mono-numbers text-amber-600 leading-tight">{{ $stats['waiting'] }}</div>
            </div>
            <div class="w-7 h-7 rounded-lg bg-yellow-50 text-amber-600 border border-yellow-200 flex items-center justify-center font-bold text-xs flex-shrink-0">
                ⏳
            </div>
        </div>

        <!-- Kartu 4: Selesai Dilayani -->
        <div class="bg-white border-2 border-slate-200/90 rounded-xl px-3 py-1.5 flex items-center justify-between shadow-2xs">
            <div>
                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-tight">Selesai Dilayani</div>
                <div class="text-base sm:text-lg font-black font-mono-numbers text-emerald-600 leading-tight">{{ $stats['completed'] }}</div>
            </div>
            <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center font-bold text-xs flex-shrink-0">
                ✓
            </div>
        </div>
    </footer>
</div>
