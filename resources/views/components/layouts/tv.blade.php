<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F8FAFC]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Layar Monitor Display Antrean Ruang Tunggu Pelayanan Statistik Terpadu (PST) BPS Provinsi Jawa Tengah">
    <title>{{ $title ?? 'Display Antrean PST - BPS Provinsi Jawa Tengah' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Merriweather:wght@700;900&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow: hidden;
            background-color: #F8FAFC;
            color: #0F172A;
            user-select: none;
            -webkit-user-select: none;
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
        .bg-bps-backdrop { background-color: #0F172A !important; }

        /* Running Marquee Animation */
        @keyframes marquee {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-100%); }
        }
        .animate-marquee {
            display: inline-block;
            white-space: nowrap;
            animation: marquee 35s linear infinite;
        }
        .animate-marquee:hover {
            animation-play-state: paused;
        }
    </style>
</head>
<body class="h-full w-full flex flex-col justify-between antialiased">
    {{ $slot }}

    @livewireScripts
</body>
</html>
