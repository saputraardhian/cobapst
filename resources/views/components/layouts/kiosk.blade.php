<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Anjungan Kios Lobi Mandiri Sentuh - Pelayanan Statistik Terpadu (PST) BPS Provinsi Jawa Tengah">
    <title>{{ $title ?? 'Kios Antrian Mandiri PST - BPS Provinsi Jawa Tengah' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700;800;900&family=Merriweather:wght@700;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F1F5F9;
            color: #0F172A;
            -webkit-tap-highlight-color: transparent;
            user-select: none;
            -webkit-user-select: none;
        }

        .font-mono-numbers {
            font-family: 'JetBrains Mono', monospace;
        }

        .font-serif-bps {
            font-family: 'Merriweather', serif;
        }

        /* Thermal Receipt Perforated Edge Styling */
        .receipt-tear-edge {
            background-image: radial-gradient(circle at 10px -5px, transparent 12px, #FFFFFF 13px);
            background-size: 20px 20px;
            background-repeat: repeat-x;
        }

        .receipt-tear-bottom {
            position: relative;
        }
        .receipt-tear-bottom::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -10px;
            height: 10px;
            background: linear-gradient(135deg, transparent 33.333%, #FFFFFF 33.333%, #FFFFFF 66.667%, transparent 66.667%),
                        linear-gradient(45deg, transparent 33.333%, #FFFFFF 33.333%, #FFFFFF 66.667%, transparent 66.667%);
            background-size: 16px 20px;
            background-repeat: repeat-x;
        }

        /* Print Media Stylesheet for 58mm & 80mm ESC/POS Receipt */
        @media print {
            body * {
                visibility: hidden !important;
            }
            #printable-thermal-ticket, #printable-thermal-ticket * {
                visibility: visible !important;
            }
            #printable-thermal-ticket {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 80mm !important;
                margin: 0 !important;
                padding: 4mm !important;
                background: #FFFFFF !important;
                color: #000000 !important;
                box-shadow: none !important;
                border: none !important;
            }
            @page {
                size: 80mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body class="min-h-full flex flex-col justify-between antialiased selection:bg-[#005288] selection:text-white">
    {{ $slot }}

    @livewireScripts
</body>
</html>
