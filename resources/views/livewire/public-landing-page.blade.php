<div 
    x-data="{
        scrollProgress: 0,
        activeSection: 'hero',
        mobileMenuOpen: false,
        modalService: null,
        
        init() {
            window.addEventListener('scroll', () => {
                const winScroll = document.documentElement.scrollTop || document.body.scrollTop;
                const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                this.scrollProgress = height > 0 ? (winScroll / height) * 100 : 0;

                // Section Spy
                const sections = ['hero', 'layanan', 'live-antrean', 'petugas-teladan', 'pengaduan', 'sso'];
                for (const section of sections) {
                    const el = document.getElementById(section);
                    if (el) {
                        const rect = el.getBoundingClientRect();
                        if (rect.top <= 200 && rect.bottom >= 200) {
                            this.activeSection = section;
                            break;
                        }
                    }
                }
            });
        },

        openServiceModal(type) {
            this.modalService = type;
        },

        closeServiceModal() {
            this.modalService = null;
        }
    }"
    class="min-h-screen bg-[#F8FAFC] text-slate-800 flex flex-col justify-between selection:bg-[#005999] selection:text-white antialiased"
>
    <!-- SCROLLYTELLING PROGRESS BAR (Top 3px gradient line) -->
    <div class="fixed top-0 left-0 h-1 bg-gradient-to-r from-[#005288] via-[#005999] to-[#F7941D] z-50 transition-all duration-150" :style="`width: ${scrollProgress}%`"></div>

    <!-- 1. FLOATING UNIFIED NAVBAR -->
    <header class="sticky top-3 z-40 px-4 sm:px-6 max-w-7xl mx-auto w-full">
        <nav class="bg-white/95 backdrop-blur-md border-2 border-slate-200/90 rounded-2xl px-4 py-2.5 flex items-center justify-between gap-4 shadow-xs">
            <!-- Brand Logo & Identity -->
            <a href="/" class="flex items-center gap-2.5 flex-shrink-0 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#005288] to-[#005999] text-white flex items-center justify-center p-1.5 shadow-xs group-hover:scale-105 transition">
                    <svg viewBox="0 0 24 24" fill="none" class="w-full h-full text-white" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="font-extrabold text-base tracking-tight text-[#005288] font-serif-bps">PRISTELA</span>
                        <span class="px-1.5 py-0.2 rounded text-[10px] font-black bg-amber-100 text-[#F7941D] border border-amber-300/40">v2.0</span>
                    </div>
                    <p class="text-[10px] text-slate-500 font-medium hidden sm:block">Pelayanan Statistik Terpadu - BPS Provinsi Jawa Tengah</p>
                </div>
            </a>

            <!-- Navigation Pills (Scrollytelling Anchor Links) -->
            <div class="hidden lg:flex items-center bg-slate-100/90 p-1 rounded-xl text-xs font-semibold text-slate-600 gap-1">
                <a href="#layanan" class="px-3 py-1.5 rounded-lg transition" :class="activeSection === 'layanan' ? 'bg-white text-[#005999] shadow-xs font-bold' : 'hover:text-slate-900'">
                    Layanan PST
                </a>
                <a href="#live-antrean" class="px-3 py-1.5 rounded-lg transition" :class="activeSection === 'live-antrean' ? 'bg-white text-[#005999] shadow-xs font-bold' : 'hover:text-slate-900'">
                    Live Antrian
                </a>
                <a href="#petugas-teladan" class="px-3 py-1.5 rounded-lg transition" :class="activeSection === 'petugas-teladan' ? 'bg-white text-[#005999] shadow-xs font-bold' : 'hover:text-slate-900'">
                    Petugas Terbaik
                </a>
                <a href="#pengaduan" class="px-3 py-1.5 rounded-lg transition" :class="activeSection === 'pengaduan' ? 'bg-white text-[#005999] shadow-xs font-bold' : 'hover:text-slate-900'">
                    Kanal Pengaduan
                </a>
            </div>

            <!-- Right Status Badge & Primary CTA -->
            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-[#6CBE45] border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-[#6CBE45] animate-pulse"></span>
                    <span>Loket Buka (08:00 - 15:30 WIB)</span>
                </div>

                <a 
                    href="/kiosk" 
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-[#005999] hover:bg-[#005288] text-white shadow-xs hover:shadow-md transition transform active:scale-95 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    <span>Ambil Tiket</span>
                </a>
            </div>
        </nav>
    </header>

    <!-- MAIN BODY CONTAINER -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 w-full space-y-16 py-8">
        <!-- 2. HERO BENTO SECTION: GATEWAY & LIVE QUEUE MONITOR -->
        <section id="hero" class="grid grid-cols-12 gap-6 items-stretch">
            <!-- Left Hero Card (White Bento Hero) -->
            <div class="col-span-12 lg:col-span-7 bg-white border-2 border-slate-200/90 rounded-3xl p-6 sm:p-10 flex flex-col justify-between shadow-xs relative overflow-hidden">
                <!-- Decorative Subtle Watermark -->
                <div class="absolute -right-16 -top-16 w-64 h-64 bg-blue-50/60 rounded-full blur-3xl pointer-events-none"></div>

                <div>
                    <!-- Integrity Badge -->
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-[#005999] border border-blue-200/80 mb-5">
                        <span class="text-amber-500">★</span>
                        <span>Pelayanan Publik Berintegritas WBK / WBBM</span>
                    </div>

                    <!-- Main Catchy Headline -->
                    <h1 class="text-3xl sm:text-4xl xl:text-5xl font-black text-slate-900 font-serif-bps tracking-tight leading-[1.18] mb-4">
                        Satu Pintu Layanan Data &amp; Konsultasi Statistik Resmi
                    </h1>

                    <!-- Descriptive Subtitle -->
                    <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-xl mb-8 font-normal">
                        Akses publikasi, data mikro sensus, rekomendasi kegiatan statistik (ROMANIK), dan konsultasi tatap muka langsung bersama statistisi ahli BPS Provinsi Jawa Tengah.
                    </p>
                </div>

                <!-- Call To Actions & Micro-trust -->
                <div>
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 mb-6">
                        <a 
                            href="/kiosk"
                            class="px-5 py-3 rounded-xl text-sm font-bold bg-[#005999] hover:bg-[#005288] text-white shadow-sm hover:shadow-md transition flex items-center gap-2 group cursor-pointer"
                        >
                            <span>Ambil Tiket Antrean Sekarang</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>

                        <a 
                            href="#pengaduan"
                            class="px-5 py-3 rounded-xl text-sm font-semibold bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 transition flex items-center gap-2 cursor-pointer"
                        >
                            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Cek Status Tiket / Pengaduan</span>
                        </a>
                    </div>

                    <!-- Trust Footer -->
                    <div class="flex items-center gap-2 text-xs font-semibold text-emerald-700 pt-4 border-t border-slate-100">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Bebas Biaya (Rp 0,-) untuk Semua Layanan Statistik</span>
                    </div>
                </div>
            </div>

            <!-- Right Hero Card: LIVE MONITOR ANTREAN (Harmonized Clean Card) -->
            <div id="live-antrean" class="col-span-12 lg:col-span-5 bg-white border-2 border-slate-200/90 rounded-3xl p-6 sm:p-7 flex flex-col justify-between text-slate-800 shadow-xs relative overflow-hidden">
                <!-- Top Header Bar -->
                <div class="flex items-center justify-between border-b border-slate-200/80 pb-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#6CBE45] animate-pulse"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-700">LIVE MONITOR ANTREAN</span>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Live Sinkron
                    </span>
                </div>

                <!-- Spotlight Active Call Ticket -->
                <div class="py-5 text-center my-auto">
                    <div class="text-xs uppercase tracking-widest text-slate-400 font-bold">Sedang Dipanggil di Loket Utama</div>
                    
                    <div class="text-6xl sm:text-7xl font-black font-mono-numbers text-[#F7941D] tracking-tight py-2 leading-none">
                        {{ $displayQueueCode }}
                    </div>

                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-[#005288] to-[#005999] text-white text-xs font-bold shadow-xs mt-1 max-w-sm">
                        <span class="w-2 h-2 rounded-full bg-[#6CBE45] animate-pulse"></span>
                        <span class="truncate">
                            {{ $activeTicket?->counter?->counter_name ?? 'Loket 2 - Konsultasi Statistik' }}
                            ({{ $activeTicket?->servedBy?->name ?? 'Hendra Wibowo, S.Si.' }})
                        </span>
                    </div>
                </div>

                <!-- 5 Loket Mini Matrix -->
                <div class="space-y-2 pt-2 border-t border-slate-200/80">
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        @foreach($counters->take(4) as $counter)
                            @php
                                $curTicket = $counter->getAttribute('current_ticket');
                            @endphp
                            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-2.5 flex items-center justify-between">
                                <span class="text-slate-700 font-medium truncate pr-1">Loket {{ $counter->counter_number }}</span>
                                <span class="font-mono-numbers font-bold text-[11px] px-2 py-0.5 rounded {{ $curTicket ? 'bg-amber-100 text-[#F7941D] border border-amber-200' : ($counter->is_open ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-200 text-slate-500') }}">
                                    {{ $curTicket ? $curTicket->ticket_number : ($counter->is_open ? 'Buka' : 'Tutup') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Loket 5 Row -->
                    @if($counters->count() >= 5)
                        <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-2.5 flex items-center justify-between text-xs">
                            <span class="text-slate-700 font-medium">Loket 5 (Presensi &amp; Tamu Dinas)</span>
                            <span class="font-bold text-[11px] px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Siap Melayani
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Footer Bar -->
                <div class="flex items-center justify-between pt-4 mt-3 border-t border-slate-200/80 text-xs">
                    <span class="text-slate-500">Total Antrean Hari Ini: <strong class="text-slate-800 font-mono-numbers">{{ $todayTicketsCount }} Orang</strong></span>
                    <a href="/antrian" class="text-[#005999] hover:text-[#005288] font-bold transition flex items-center gap-1 group">
                        <span>Tampilan TV Widescreen</span>
                        <span class="group-hover:translate-x-0.5 transition">→</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- 3. SECTION 2: 4 PILAR LAYANAN TERPADU PST (BENTO GRID) -->
        <section id="layanan" class="space-y-6">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 text-xs font-extrabold uppercase tracking-widest text-[#005999] mb-1">
                        <span>STANDAR PELAYANAN PUBLIK</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 font-serif-bps tracking-tight">
                        4 Pilar Layanan Terpadu PST
                    </h2>
                </div>
                <p class="text-xs text-slate-500 max-w-md md:text-right">
                    Standar Pelayanan Prima sesuai UU No. 25 Tahun 2009 dan Maklumat Pelayanan BPS Provinsi Jawa Tengah.
                </p>
            </div>

            <!-- 4 Bento Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Pilar 1: Perpustakaan Statistik -->
                <div 
                    x-on:click="openServiceModal('perpustakaan')"
                    class="bg-white border-2 border-slate-200/90 hover:border-[#005288] rounded-3xl p-6 flex flex-col justify-between shadow-xs hover:shadow-md hover:-translate-y-1 transition duration-300 cursor-pointer group"
                >
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-[#005999] flex items-center justify-center p-2.5 border border-blue-100 group-hover:scale-110 transition">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-slate-100 text-slate-600">
                                LOKET 1
                            </span>
                        </div>

                        <h3 class="text-base font-extrabold text-slate-900 mb-2 group-hover:text-[#005999] transition">
                            Perpustakaan Statistik
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">
                            Membaca dan meminjam buku statistik terbitan BPS Provinsi Jawa Tengah secara fisik di ruang baca atau akses digital.
                        </p>

                        <!-- Chips -->
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600">
                                Buku/Koleksi Fisik
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600">
                                Aplikasi Simpel
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                        <span class="text-slate-400 font-medium">SLA: 15 Menit</span>
                        <span class="font-bold text-[#005999] group-hover:translate-x-1 transition flex items-center gap-0.5">
                            Pilih →
                        </span>
                    </div>
                </div>

                <!-- Pilar 2: Konsultasi Statistik -->
                <div 
                    x-on:click="openServiceModal('konsultasi')"
                    class="bg-white border-2 border-slate-200/90 hover:border-[#F7941D] rounded-3xl p-6 flex flex-col justify-between shadow-xs hover:shadow-md hover:-translate-y-1 transition duration-300 cursor-pointer group"
                >
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-[#F7941D] flex items-center justify-center p-2.5 border border-amber-100 group-hover:scale-110 transition">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-slate-100 text-slate-600">
                                LOKET 2
                            </span>
                        </div>

                        <h3 class="text-base font-extrabold text-slate-900 mb-2 group-hover:text-[#F7941D] transition">
                            Konsultasi Statistik
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">
                            Diskusi teknis dengan statistisi ahli perihal data PDRB, kemiskinan, inflasi, ketenagakerjaan, serta rekomendasi metode survei.
                        </p>

                        <!-- Chips -->
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600">
                                Statistik Sektoral
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600">
                                Metadata &amp; Metodologi
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                        <span class="text-slate-400 font-medium">SLA: 45 Menit</span>
                        <span class="font-bold text-[#F7941D] group-hover:translate-x-1 transition flex items-center gap-0.5">
                            Pilih →
                        </span>
                    </div>
                </div>

                <!-- Pilar 3: Pembelian & Data Mikro -->
                <div 
                    x-on:click="openServiceModal('pembelian')"
                    class="bg-white border-2 border-slate-200/90 hover:border-[#6CBE45] rounded-3xl p-6 flex flex-col justify-between shadow-xs hover:shadow-md hover:-translate-y-1 transition duration-300 cursor-pointer group"
                >
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#6CBE45] flex items-center justify-center p-2.5 border border-emerald-100 group-hover:scale-110 transition">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                </svg>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-slate-100 text-slate-600">
                                LOKET 3
                            </span>
                        </div>

                        <h3 class="text-base font-extrabold text-slate-900 mb-2 group-hover:text-[#6CBE45] transition">
                            Pembelian &amp; Data Mikro
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">
                            Penyediaan dataset mikro raw data sensus, susenas, sakernas, serta peta digital tematik/SHP untuk keperluan riset &amp; skripsi.
                        </p>

                        <!-- Chips -->
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600">
                                Peta Digital (SHP)
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600">
                                Raw Data Riset
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                        <span class="text-slate-400 font-medium">SLA: 30 Menit</span>
                        <span class="font-bold text-[#6CBE45] group-hover:translate-x-1 transition flex items-center gap-0.5">
                            Pilih →
                        </span>
                    </div>
                </div>

                <!-- Pilar 4: Rekomendasi Kegiatan Statistik (ROMANIK) -->
                <div 
                    x-on:click="openServiceModal('rekomendasi')"
                    class="bg-white border-2 border-slate-200/90 hover:border-purple-600 rounded-3xl p-6 flex flex-col justify-between shadow-xs hover:shadow-md hover:-translate-y-1 transition duration-300 cursor-pointer group"
                >
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center p-2.5 border border-purple-100 group-hover:scale-110 transition">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-slate-100 text-slate-600">
                                LOKET 4
                            </span>
                        </div>

                        <h3 class="text-base font-extrabold text-slate-900 mb-2 group-hover:text-purple-600 transition">
                            Rekomendasi (ROMANIK)
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">
                            Pemberian rekomendasi kegiatan statistik sektoral bagi OPD Pemprov Jawa Tengah dan instansi vertikal sesuai regulasi Satu Data Indonesia.
                        </p>

                        <!-- Chips -->
                        <div class="flex flex-wrap gap-1.5 mb-5">
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600">
                                Rekomendasi Survei
                            </span>
                            <span class="px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600">
                                Metadata Statistik
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-slate-100 text-xs">
                        <span class="text-slate-400 font-medium">SLA: 15 Hari Kerja</span>
                        <span class="font-bold text-purple-600 group-hover:translate-x-1 transition flex items-center gap-0.5">
                            Pilih →
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. SECTION 3: HALL OF FAME PETUGAS TELADAN (INTEGRASI ELENA) -->
        <section id="petugas-teladan" class="bg-white border-2 border-slate-200/90 rounded-3xl p-6 sm:p-8 shadow-xs space-y-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-[#F7941D] border border-amber-300/40 mb-2">
                        <span>★ Inovasi Kesejahteraan ASN - Triwulan 3 2026</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 font-serif-bps tracking-tight">
                        Hall of Fame Petugas PST Teladan
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Dedikasi prima dalam public service delivery, SLA tercepat, dan predikat perilaku kerja terbaik berdasarkan hasil evaluasi terpadu ELENA.
                    </p>
                </div>

                <div class="flex items-center gap-2 self-start sm:self-center">
                    <span class="text-xs text-slate-400 font-medium">Periode Saat Ini:</span>
                    <span class="px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        JULI - AGUSTUS 2026
                    </span>
                </div>
            </div>

            <!-- 3 PODIUM CARDS (Bento Podium Layout) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end pt-2">
                <!-- RANK 2: PERINGKAT 2 (SILVER PODIUM) -->
                <div class="order-2 md:order-1 bg-slate-50 border-2 border-slate-200/90 rounded-3xl p-6 flex flex-col justify-between text-center shadow-xs">
                    <div>
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-200 text-slate-700 mb-4">
                            PERINGKAT 2
                        </div>

                        <div class="w-16 h-16 rounded-2xl bg-slate-200 text-slate-700 font-black text-xl flex items-center justify-center mx-auto mb-3 shadow-xs">
                            DN
                        </div>

                        <h3 class="text-base font-extrabold text-slate-900">Dina Nuryita, S.Tr.Stat.</h3>
                        <p class="text-xs text-slate-500 mb-4">Loket 1 - Perpustakaan &amp; Rujukan</p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-200 text-xs">
                        <div class="bg-white rounded-xl p-2 border border-slate-200">
                            <div class="text-[10px] text-slate-400">Skor Kinerja</div>
                            <div class="font-bold text-slate-800 font-mono-numbers">3.94 / 4.00</div>
                        </div>
                        <div class="bg-white rounded-xl p-2 border border-slate-200">
                            <div class="text-[10px] text-slate-400">Kepuasan</div>
                            <div class="font-bold text-[#6CBE45] font-mono-numbers">98.1%</div>
                        </div>
                    </div>
                </div>

                <!-- RANK 1: PETUGAS TELADAN 1 (GOLD SPOTLIGHT PODIUM - ELEVATED) -->
                <div class="order-1 md:order-2 bg-gradient-to-b from-amber-50/70 via-white to-white border-2 border-[#F7941D]/60 rounded-3xl p-6 sm:p-7 flex flex-col justify-between text-center shadow-md relative md:-translate-y-3">
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2">
                        <span class="px-4 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-gradient-to-r from-amber-500 to-[#F7941D] text-white shadow-xs">
                            ★ PETUGAS TELADAN 1
                        </span>
                    </div>

                    <div class="pt-2">
                        <div class="w-20 h-20 rounded-2xl bg-[#005999] ring-4 ring-amber-400 text-white font-black text-2xl flex items-center justify-center mx-auto mb-3 shadow-md">
                            HW
                        </div>

                        <h3 class="text-lg font-black text-slate-900">Hendra Wibowo, S.Si.</h3>
                        <p class="text-xs text-slate-500 mb-5">Loket 2 - Konsultasi Statistik Sektoral</p>
                    </div>

                    <!-- 3 Stats Matrix -->
                    <div class="grid grid-cols-3 gap-2 py-3 border-y border-amber-200/50 text-xs mb-4">
                        <div>
                            <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Nilai Akhir</div>
                            <div class="text-lg font-black text-[#F7941D] font-mono-numbers">99.8</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Puas BPS</div>
                            <div class="text-lg font-black text-[#6CBE45] font-mono-numbers">4.98</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Total Layanan</div>
                            <div class="text-lg font-black text-[#005999] font-mono-numbers">162</div>
                        </div>
                    </div>

                    <div class="inline-flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-xl bg-amber-100 text-amber-900 text-xs font-bold border border-amber-300/40">
                        <span>★ Pegawai Teladan BPS Jateng (Piagam Resmi)</span>
                    </div>
                </div>

                <!-- RANK 3: PERINGKAT 3 (BRONZE PODIUM) -->
                <div class="order-3 bg-slate-50 border-2 border-slate-200/90 rounded-3xl p-6 flex flex-col justify-between text-center shadow-xs">
                    <div>
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-200 text-slate-700 mb-4">
                            PERINGKAT 3
                        </div>

                        <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-900 font-black text-xl flex items-center justify-center mx-auto mb-3 shadow-xs">
                            RS
                        </div>

                        <h3 class="text-base font-extrabold text-slate-900">Rino Septiani, M.Stat.</h3>
                        <p class="text-xs text-slate-500 mb-4">Loket 4 - Rekomendasi Statistik (ROMANIK)</p>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-200 text-xs">
                        <div class="bg-white rounded-xl p-2 border border-slate-200">
                            <div class="text-[10px] text-slate-400">Skor Kinerja</div>
                            <div class="font-bold text-slate-800 font-mono-numbers">3.87 / 4.00</div>
                        </div>
                        <div class="bg-white rounded-xl p-2 border border-slate-200">
                            <div class="text-[10px] text-slate-400">Kepuasan</div>
                            <div class="font-bold text-[#6CBE45] font-mono-numbers">96.2%</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. SECTION 4: TRANSPARANSI LAYANAN & KANAL PENGADUAN (2 BENTO COLUMNS) -->
        <section id="pengaduan" class="grid grid-cols-12 gap-6 items-stretch">
            <!-- Left Card: LACAK STATUS TIKET & PENGADUAN (White Bento Card) -->
            <div id="lacak" class="col-span-12 lg:col-span-7 bg-white border-2 border-slate-200/90 rounded-3xl p-6 sm:p-8 flex flex-col justify-between shadow-xs">
                <div>
                    <div class="text-[11px] font-extrabold uppercase tracking-widest text-[#005999] mb-1">
                        TRANSPARANSI LAYANAN
                    </div>
                    <h2 class="text-2xl font-black text-slate-900 font-serif-bps tracking-tight mb-2">
                        Lacak Status Tiket Layanan &amp; Pengaduan
                    </h2>
                    <p class="text-xs text-slate-600 leading-relaxed mb-6">
                        Masukkan Nomor Tiket Pengaduan (ADU-2026-XXXX) atau Kode Antrean Kiosk untuk memantau progres disposisi petugas secara real-time.
                    </p>

                    <!-- Interactive Livewire Search Input -->
                    <form wire:submit="trackTicket" class="flex flex-col sm:flex-row gap-2.5 mb-4">
                        <div class="relative flex-1">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                wire:model="searchCode" 
                                placeholder="Contoh: ADU-2026-0042 atau A-024"
                                class="w-full pl-10 pr-4 py-3 rounded-xl text-xs font-medium bg-slate-50 border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#005999] focus:border-transparent transition"
                            />
                        </div>

                        <button 
                            type="submit"
                            wire:loading.attr="disabled"
                            class="px-5 py-3 rounded-xl text-xs font-bold bg-[#005999] hover:bg-[#005288] text-white shadow-xs transition flex items-center justify-center gap-2 flex-shrink-0 cursor-pointer"
                        >
                            <span wire:loading.remove>Lacak Progres</span>
                            <span wire:loading>Memeriksa...</span>
                        </button>
                    </form>

                    <!-- Quick Example Pills -->
                    <div class="flex items-center flex-wrap gap-2 text-xs text-slate-500">
                        <span>Contoh nomor aktif:</span>
                        <button 
                            type="button" 
                            wire:click="trackTicket('ADU-2026-0042')"
                            class="px-2 py-0.5 rounded-md font-mono-numbers text-[11px] font-bold bg-slate-100 hover:bg-blue-50 text-[#005999] border border-slate-200 transition cursor-pointer"
                        >
                            ADU-2026-0042
                        </button>
                        <button 
                            type="button" 
                            wire:click="trackTicket('PST-2026-0112')"
                            class="px-2 py-0.5 rounded-md font-mono-numbers text-[11px] font-bold bg-slate-100 hover:bg-emerald-50 text-[#6CBE45] border border-slate-200 transition cursor-pointer"
                        >
                            PST-2026-0112
                        </button>
                        <button 
                            type="button" 
                            wire:click="trackTicket('A-024')"
                            class="px-2 py-0.5 rounded-md font-mono-numbers text-[11px] font-bold bg-slate-100 hover:bg-amber-50 text-[#F7941D] border border-slate-200 transition cursor-pointer"
                        >
                            A-024
                        </button>
                    </div>

                    <!-- Search Result Card -->
                    @if($searchResult)
                        <div class="mt-6 p-4 rounded-2xl border {{ $searchResult['found'] ? 'bg-slate-50 border-slate-200' : 'bg-rose-50 border-rose-200' }} transition">
                            @if($searchResult['found'])
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-[#005999] text-white">
                                            {{ $searchResult['category'] }}
                                        </span>
                                        <span class="text-xs font-mono-numbers font-bold text-slate-700">
                                            {{ $searchResult['code'] }}
                                        </span>
                                    </div>
                                    <span class="text-[11px] font-bold text-emerald-600">
                                        {{ $searchResult['status_label'] }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-bold text-slate-900 mb-1">{{ $searchResult['title'] }}</h4>
                                <p class="text-xs text-slate-600 mb-2">{{ $searchResult['description'] }}</p>
                                <div class="text-[11px] text-slate-500 bg-white p-2.5 rounded-xl border border-slate-200/80">
                                    <strong class="text-slate-700">Tindak Lanjut:</strong> {{ $searchResult['feedback'] }}
                                </div>
                            @else
                                <div class="flex items-center gap-2 text-rose-700 text-xs font-medium">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $searchResult['message'] }}</span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="text-[11px] text-slate-400 mt-6 pt-3 border-t border-slate-100">
                    Kerahasiaan data pelapor dilindungi Undang-Undang Keterbukaan Informasi Publik.
                </div>
            </div>

            <!-- Right Card: KANAL PENGADUAN KHUSUS PST (Deep Navy Bento Card) -->
            <div class="col-span-12 lg:col-span-5 bg-gradient-to-br from-[#005288] to-[#003D66] border-2 border-blue-700 rounded-3xl p-6 sm:p-8 flex flex-col justify-between text-white shadow-md relative overflow-hidden">
                <div>
                    <div class="inline-flex items-center gap-1.5 text-[11px] font-black uppercase tracking-widest text-sky-200 mb-2">
                        <span>WHISTLEBLOWING &amp; LAYANAN ASPIRASI</span>
                    </div>

                    <h2 class="text-2xl font-black text-white font-serif-bps tracking-tight mb-3">
                        Kanal Pengaduan Khusus PST
                    </h2>

                    <p class="text-xs text-sky-100 leading-relaxed mb-6 font-normal">
                        Menemukan ketidaksesuaian standar pelayanan, kendala fasilitas, atau indikasi gratifikasi? Identitas pelapor dijamin kerahasiaannya.
                    </p>

                    <!-- Feature Checklist -->
                    <div class="space-y-3 mb-8">
                        <div class="flex items-center gap-2.5 text-xs font-semibold text-white">
                            <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-[#6CBE45] flex items-center justify-center flex-shrink-0">
                                ✓
                            </div>
                            <span>Respon Terverifikasi 1x24 Jam Kerja</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-xs font-semibold text-white">
                            <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-[#6CBE45] flex items-center justify-center flex-shrink-0">
                                ✓
                            </div>
                            <span>Terintegrasi langsung ke e-Lapor &amp; SPI BPS Jateng</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-xs font-semibold text-white">
                            <div class="w-5 h-5 rounded-full bg-emerald-500/20 text-[#6CBE45] flex items-center justify-center flex-shrink-0">
                                ✓
                            </div>
                            <span>Perlindungan Anonimitas 100% Terjamin</span>
                        </div>
                    </div>
                </div>

                <div>
                    <a 
                        href="/pengaduan" 
                        class="w-full py-3.5 px-4 rounded-xl text-xs font-extrabold bg-white text-[#005288] hover:bg-sky-50 shadow-md transition flex items-center justify-center gap-2 group cursor-pointer"
                    >
                        <span>Buat Laporan / Pengaduan Baru</span>
                        <span class="group-hover:translate-x-1 transition font-bold">→</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- 6. SECTION 5: GATEWAY SINGLE SIGN-ON (SSO) PRISTELA -->
        <section id="sso" class="bg-gradient-to-r from-[#005288] via-[#005999] to-[#005288] border-2 border-blue-400/20 rounded-3xl p-6 sm:p-7 text-white shadow-md flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
            <div class="flex items-center gap-4 z-10">
                <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 text-white flex items-center justify-center p-3 flex-shrink-0 shadow-inner">
                    <svg class="w-full h-full" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-extrabold uppercase tracking-widest text-[#FDE047]">PORTAL KHUSUS PEGAWAI &amp; OPERATOR</span>
                    </div>
                    <h3 class="text-lg font-black text-white font-serif-bps">Gateway Single Sign-On (SSO) PRISTELA</h3>
                    <p class="text-xs text-sky-100 max-w-2xl mt-0.5">
                        Akses internal petugas loket, koordinator jadwal piket PRISTI, rekapitulasi kepuasan STATISFY, dan pelaporan kinerja ELENA.
                    </p>
                </div>
            </div>

            <div class="flex-shrink-0 z-10 w-full md:w-auto">
                <a 
                    href="/admin/login" 
                    class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-xs font-bold bg-white text-[#005288] hover:bg-slate-100 shadow-md transition group cursor-pointer"
                >
                    <span>Masuk SSO Pegawai</span>
                    <span class="group-hover:translate-x-1 transition font-bold">→</span>
                </a>
            </div>
        </section>
    </main>

    <!-- 7. INSTITUTIONAL FOOTER -->
    <footer class="bg-white border-t-2 border-slate-200/90 mt-16 pt-12 pb-8 text-slate-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 w-full space-y-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1: Instansi -->
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-[#005288] text-white flex items-center justify-center font-bold text-xs">
                            BPS
                        </div>
                        <span class="font-extrabold text-base text-[#005288] font-serif-bps">PRISTELA</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Sistem integrasi digital Pelayanan Statistik Terpadu Jawa Tengah: konsolidasi layanan publik, antrean, jadwal piket, survei kepuasan, dan evaluasi kinerja.
                    </p>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-[#6CBE45] border border-emerald-200">
                        <span>● PREDIKAT WBK / WBBM</span>
                    </div>
                </div>

                <!-- Col 2: Direktori Modul -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900">Direktori Modul Terpadu</h4>
                    <ul class="text-xs text-slate-600 space-y-1.5">
                        <li><a href="/kiosk" class="hover:text-[#005999] transition">• PST: Antrean &amp; Buku Tamu</a></li>
                        <li><a href="/antrian" class="hover:text-[#005999] transition">• STATISFY: Survei Kepuasan HP</a></li>
                        <li><a href="/admin" class="hover:text-[#005999] transition">• PRISTI: Presensi &amp; Jadwal Petugas PST</a></li>
                        <li><a href="/pengaduan" class="hover:text-[#005999] transition">• PENGADUAN: Whistleblowing System</a></li>
                        <li><a href="/admin" class="hover:text-[#005999] transition">• ELENA: Evaluasi Kinerja &amp; Piagam</a></li>
                    </ul>
                </div>

                <!-- Col 3: Jam Pelayanan -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900">Jam Pelayanan Kedinasan</h4>
                    <div class="text-xs text-slate-600 space-y-1">
                        <div class="flex justify-between">
                            <span>Senin - Kamis</span>
                            <strong class="text-slate-800">08:00 - 15:30 WIB</strong>
                        </div>
                        <div class="flex justify-between">
                            <span>Jumat</span>
                            <strong class="text-slate-800">08:00 - 15:00 WIB</strong>
                        </div>
                        <div class="flex justify-between text-rose-500 font-semibold pt-1">
                            <span>Sabtu - Minggu &amp; Libur</span>
                            <span>TUTUP</span>
                        </div>
                        <p class="text-[11px] text-slate-400 pt-1">Istirahat: Setiap hari kerja 12:00 - 13:00 WIB</p>
                    </div>
                </div>

                <!-- Col 4: Kontak & Lokasi -->
                <div class="space-y-2">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900">Kantor PST BPS Jateng</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Jl. Pahlawan No. 6, Pleburan, Kec. Semarang Selatan, Kota Semarang, Jawa Tengah 50241
                    </p>
                    <div class="text-xs text-slate-600 space-y-0.5">
                        <div>Telepon: (024) 8412821</div>
                        <div>Email: pst3300@bps.go.id</div>
                        <div>Portal: jateng.bps.go.id</div>
                    </div>
                </div>
            </div>

            <!-- Bottom Copyright Bar -->
            <div class="pt-6 border-t border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    © 2026 Badan Pusat Statistik Provinsi Jawa Tengah. Hak Cipta Dilindungi.
                </div>
                <div class="flex items-center gap-4">
                    <a href="#layanan" class="hover:text-slate-900 transition">Standar Pelayanan</a>
                    <span>•</span>
                    <a href="#hero" class="hover:text-slate-900 transition">Maklumat Pelayanan</a>
                    <span>•</span>
                    <a href="#pengaduan" class="hover:text-slate-900 transition">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- INTERACTIVE SERVICE DETAILS MODAL (Scrollytelling Drawer) -->
    <div 
        x-show="modalService !== null" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            x-on:click.away="closeServiceModal()"
            class="bg-white border-2 border-slate-200/90 rounded-3xl max-w-lg w-full p-6 shadow-2xl space-y-4"
        >
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-900" x-text="
                    modalService === 'perpustakaan' ? 'Rincian Layanan: Perpustakaan Statistik' :
                    (modalService === 'konsultasi' ? 'Rincian Layanan: Konsultasi Statistik Sektoral' :
                    (modalService === 'pembelian' ? 'Rincian Layanan: Pembelian & Data Mikro' : 'Rincian Layanan: Rekomendasi Kegiatan Statistik (ROMANIK)'))
                "></h3>
                <button type="button" x-on:click="closeServiceModal()" class="text-slate-400 hover:text-slate-600 font-bold p-1 cursor-pointer">
                    ✕
                </button>
            </div>

            <div class="text-xs text-slate-600 space-y-3">
                <p x-show="modalService === 'perpustakaan'">
                    Layanan ruang baca fisik dan digital PST BPS Jawa Tengah menyediakan ratusan publikasi berkala, buku statistik tahunan (Jateng Dalam Angka), indikator sosial ekonomi, serta bantuan penelusuran pustaka digital via aplikasi Simpel.
                </p>
                <p x-show="modalService === 'konsultasi'">
                    Layanan tatap muka dengan statistisi ahli perihal konsep, definisi, metodologi sensus, perhitungan PDRB, inflasi, kemiskinan, ketenagakerjaan, dan survei sosial ekonomi.
                </p>
                <p x-show="modalService === 'pembelian'">
                    Layanan penyediaan raw data mikro level individu/rumah tangga (anonymized) dari Susenas, Sakernas, Sensus Pertanian, dan peta digital SHP untuk mahasiswa, peneliti, dan lembaga riset.
                </p>
                <p x-show="modalService === 'rekomendasi'">
                    Layanan pemberian rekomendasi bagi Organisasi Perangkat Daerah (OPD) dan instansi pemerintah yang hendak melaksanakan survei statistik sektoral sesuai UU No. 16 Tahun 1997 dan Perpres Satu Data Indonesia.
                </p>

                <div class="bg-slate-50 p-3 rounded-xl border border-slate-200/80 space-y-1.5">
                    <div class="flex justify-between font-semibold text-slate-700">
                        <span>Biaya Retribusi:</span>
                        <strong class="text-emerald-600">Rp 0,- (GRATIS)</strong>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Persyaratan:</span>
                        <span>Identitas KTP / KTM Mahasiswa</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button 
                    type="button" 
                    x-on:click="closeServiceModal()"
                    class="px-4 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition cursor-pointer"
                >
                    Tutup
                </button>
                <a 
                    href="/kiosk"
                    class="px-5 py-2 rounded-xl text-xs font-bold bg-[#005999] hover:bg-[#005288] text-white shadow-xs transition cursor-pointer"
                >
                    Ambil Tiket di Kiosk Lobi →
                </a>
            </div>
        </div>
    </div>
</div>
