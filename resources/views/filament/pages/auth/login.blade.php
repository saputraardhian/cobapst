<div x-data="{ showForgotModal: false, showPassword: false }" class="w-full max-w-[420px]">
    {{-- SSO LOGIN CARD CONTAINER --}}
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-200/50 p-6 sm:p-7 relative">
        {{-- TOP PADLOCK BADGE --}}
        <div class="w-12 h-12 rounded-2xl bg-sky-50/90 border border-sky-100 flex items-center justify-center mx-auto mb-3.5 text-[#005999] shadow-2xs">
            <svg class="w-5 h-5 text-[#005999]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
        </div>

        {{-- HEADING --}}
        <h1 class="text-2xl font-bold text-slate-900 font-serif tracking-tight text-center">
            Login SSO Pegawai
        </h1>

        {{-- SUBHEADING --}}
        <p class="text-xs text-slate-500 text-center mt-1.5 mb-5 leading-relaxed max-w-xs mx-auto">
            Silakan masukkan kredensial akun dinas Anda untuk masuk ke sistem PRISTELA
        </p>

        {{-- AUTHENTICATION FORM --}}
        <form wire:submit.prevent="authenticate" class="space-y-3.5 text-left">
            {{-- USERNAME / EMAIL DINAS INPUT --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Username atau Email Dinas
                </label>
                <div class="sso-input-container flex items-center rounded-xl border @error('data.login') border-rose-300 bg-rose-50/30 ring-1 ring-rose-200 @else border-slate-200 bg-slate-50/70 focus-within:border-[#005288] focus-within:bg-white focus-within:ring-2 focus-within:ring-sky-100 @enderror transition shadow-2xs px-3.5 py-2">
                    <svg class="w-4 h-4 text-slate-400 shrink-0 mr-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    <input 
                        type="text" 
                        wire:model="data.login" 
                        autofocus
                        autocomplete="username"
                        class="flex-1 min-w-0 bg-transparent text-sm font-mono text-slate-800"
                    />
                </div>
                @error('data.login')
                    <p class="text-xs text-rose-600 mt-1 font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            {{-- KATA SANDI SSO INPUT --}}
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-semibold text-slate-700">
                        Kata Sandi SSO
                    </label>
                    <button 
                        type="button" 
                        @click="showForgotModal = true" 
                        class="text-xs font-medium text-[#005999] hover:underline cursor-pointer"
                    >
                        Lupa Sandi?
                    </button>
                </div>
                <div class="sso-input-container flex items-center rounded-xl border @error('data.password') border-rose-300 bg-rose-50/30 ring-1 ring-rose-200 @else border-slate-200 bg-slate-50/70 focus-within:border-[#005288] focus-within:bg-white focus-within:ring-2 focus-within:ring-sky-100 @enderror transition shadow-2xs px-3.5 py-2">
                    <svg class="w-4 h-4 text-slate-400 shrink-0 mr-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <input 
                        :type="showPassword ? 'text' : 'password'" 
                        wire:model="data.password" 
                        autocomplete="current-password"
                        class="flex-1 min-w-0 bg-transparent text-sm font-mono text-slate-800 tracking-wider"
                    />
                    <button 
                        type="button" 
                        @click="showPassword = !showPassword" 
                        class="shrink-0 ml-2 p-1 text-slate-400 hover:text-slate-700 transition cursor-pointer select-none rounded-md hover:bg-slate-100"
                        :title="showPassword ? 'Sembunyikan Kata Sandi' : 'Tampilkan Kata Sandi'"
                    >
                        <svg x-show="!showPassword" class="w-4 h-4 text-slate-400 hover:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg x-show="showPassword" x-cloak class="w-4 h-4 text-[#005999]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                @error('data.password')
                    <p class="text-xs text-rose-600 mt-1 font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            {{-- REMEMBER & TLS ROW --}}
            <div class="flex items-center justify-between pt-0.5">
                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input 
                        type="checkbox" 
                        wire:model="data.remember" 
                        class="w-4 h-4 rounded border-slate-300 text-[#005288] focus:ring-[#005288]/20 focus:ring-2 cursor-pointer accent-[#005288]"
                    />
                    <span class="text-xs text-slate-600 font-medium">Ingat sesi di perangkat ini</span>
                </label>

                <div class="inline-flex items-center gap-1.5 text-[11px] font-mono font-semibold text-emerald-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    TLS 1.3
                </div>
            </div>

            {{-- SUBMIT BUTTON (MASUK SISTEM ->) --}}
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="w-full mt-3 py-2.5 sm:py-3 px-4 rounded-xl text-white font-semibold text-sm shadow-md transition flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75 disabled:cursor-wait"
                style="background-color: #005288 !important; color: #ffffff !important;"
            >
                <span>Masuk Sistem</span>
                <svg class="w-4 h-4 text-white shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </button>

            {{-- 1-CLICK QUICK BYPASS BUTTON --}}
            <div class="pt-1.5">
                <button 
                    type="button" 
                    wire:click="bypassLogin('super_admin')" 
                    class="w-full py-2 px-3 rounded-xl bg-amber-50 hover:bg-amber-100/90 active:scale-[0.99] border border-amber-200 text-amber-800 font-semibold text-xs transition flex items-center justify-center gap-1.5 cursor-pointer shadow-2xs"
                >
                    <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                    </svg>
                    <span>⚡ Bypass Masuk (Akses Cepat Super Admin)</span>
                </button>
            </div>
        </form>

        {{-- BPS SINGLE SIGN-ON INFORMATION BANNER --}}
        <div class="mt-4 p-3 rounded-xl bg-sky-50/70 border border-sky-100 flex items-start gap-2.5 text-left">
            <div class="text-[#005999] shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed">
                <strong class="text-slate-800 font-semibold">Autentikasi Terpadu BPS:</strong> Gunakan akun Single Sign On resmi BPS. Kendala akun atau reset sandi? <button type="button" @click="showForgotModal = true" class="text-[#005999] font-semibold hover:underline cursor-pointer">Hubungi IPDS Ext. 120</button>.
            </p>
        </div>

        {{-- MICRO SECURITY LABEL --}}
        <div class="mt-3.5 flex items-center justify-center gap-1.5 text-[11px] text-slate-400 font-mono">
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
            </svg>
            <span>Sesi aman terenkripsi SPBE BPS Jateng</span>
        </div>
    </div>

    {{-- MODAL BANTUAN IPDS & LUPA KATA SANDI --}}
    <div 
        x-show="showForgotModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs transition-opacity"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            @click.away="showForgotModal = false"
            class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-200 text-left relative"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#005999] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm">Bantuan SSO & Reset Kata Sandi</h3>
                </div>
                <button @click="showForgotModal = false" class="text-slate-400 hover:text-slate-600 p-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="py-4 space-y-3 text-xs text-slate-600 leading-relaxed">
                <p>
                    Pengelolaan kredensial akun dinas terpusat pada sistem SPBE BPS. Jika Anda lupa kata sandi atau terkendala akses SSO, silakan hubungi tim Helpdesk IPDS:
                </p>
                <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-200/80 space-y-2 font-mono text-[11px]">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Saluran Telepon:</span>
                        <span class="font-bold text-slate-800">Ext. 120 / (024) 8413621</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Email Helpdesk:</span>
                        <a href="mailto:ipds3300@bps.go.id" class="font-bold text-[#005999] hover:underline">ipds3300@bps.go.id</a>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">Lokasi:</span>
                        <span class="text-slate-700">Lantai 2, BPS Provinsi Jawa Tengah</span>
                    </div>
                </div>
                <p class="text-[11px] text-slate-400">
                    Jam Layanan: Senin - Kamis 08.00 - 15.30 WIB | Jumat 08.30 - 15.00 WIB.
                </p>
            </div>

            <div class="pt-2 text-right">
                <button 
                    type="button" 
                    @click="showForgotModal = false"
                    class="px-4 py-2 bg-[#005288] hover:bg-[#00426f] text-white text-xs font-semibold rounded-lg shadow-sm transition"
                >
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>
