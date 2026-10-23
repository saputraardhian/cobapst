<!DOCTYPE html>
<html lang="id" class="scroll-smooth bg-[#F8FAFC] text-slate-800 antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PRISTELA - Portal Layanan Publik Terpadu & Konsultasi Statistik Resmi BPS Provinsi Jawa Tengah. Layanan data mikro, sensus, perpustakaan, rekomendasi kegiatan statistik (ROMANIK), dan pemantauan antrean lobi real-time. Bebas Biaya (Rp 0,-).">
    <meta name="keywords" content="PRISTELA, BPS Provinsi Jawa Tengah, Layanan Statistik Terpadu, Data Mikro, Konsultasi Statistik, Sensus BPS, ROMANIK, Antrean PST">
    <meta name="author" content="BPS Provinsi Jawa Tengah">
    <meta property="og:title" content="{{ $title ?? 'PRISTELA - Portal Layanan Publik & Konsultasi Statistik BPS Jateng' }}">
    <meta property="og:description" content="Satu Pintu Layanan Data & Konsultasi Statistik Resmi BPS Provinsi Jawa Tengah. Bebas Biaya (Rp 0,-).">
    <meta property="og:type" content="website">
    
    <title>{{ $title ?? 'PRISTELA - Portal Layanan Publik & Konsultasi Statistik BPS Jateng' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Merriweather:wght@700;900&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        html, body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC !important;
            color: #0F172A;
        }
        .font-mono-numbers {
            font-family: 'JetBrains Mono', monospace;
        }
        .font-serif-bps {
            font-family: 'Merriweather', serif;
        }
        .text-bps-orange { color: #F7941D !important; }
        .bg-bps-orange { background-color: #F7941D !important; }
        .border-bps-orange { border-color: #F7941D !important; }
        .text-bps-navy { color: #005288 !important; }
        .bg-bps-navy { background-color: #005288 !important; }
        .text-bps-blue { color: #005999 !important; }
        .bg-bps-blue { background-color: #005999 !important; }
        .text-bps-green { color: #6CBE45 !important; }
        .bg-bps-green { background-color: #6CBE45 !important; }
        .text-bps-yellow { color: #FDE047 !important; }
        .bg-bps-backdrop { background-color: #204eb7ff !important; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between selection:bg-[#005999] selection:text-white">
    {{ $slot }}

    @livewireScripts
</body>
</html>
