<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sukha — {{ config('app.name', 'Sukha') }}</title>
    <!-- Favicon Permanente -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon.png') }}?v=4">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}?v=4">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v=4">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}?v=4">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=4">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-playfair { font-family: 'Playfair Display', Georgia, serif; }
        .bg-sukha-sage { background-color: #7D9B76; }
        .text-sukha-sage { color: #7D9B76; }
        .border-sukha-sage { border-color: #7D9B76; }
        .bg-ivory { background-color: #FAF7F0; }
    </style>
</head>
<body class="antialiased min-h-screen" style="background-color: #F0EDE4; background-image: radial-gradient(circle at 20% 20%, #D4E5D2 0%, transparent 40%), radial-gradient(circle at 80% 80%, #EDE8DC 0%, transparent 40%);">

    <!-- Decorative leaf SVGs -->
    <div class="fixed top-0 left-0 w-64 h-64 pointer-events-none opacity-10">
        <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 180 C20 180 40 80 140 20 C140 20 160 120 20 180Z" fill="#7D9B76"/>
            <path d="M20 180 C80 160 140 20 140 20" stroke="#5A7A53" stroke-width="1.5" stroke-dasharray="4 3"/>
        </svg>
    </div>
    <div class="fixed bottom-0 right-0 w-80 h-80 pointer-events-none opacity-10 rotate-180">
        <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 180 C20 180 40 80 140 20 C140 20 160 120 20 180Z" fill="#8FAF88"/>
            <path d="M20 180 C80 160 140 20 140 20" stroke="#5A7A53" stroke-width="1.5" stroke-dasharray="4 3"/>
        </svg>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4">

        <!-- Logo + Brand -->
        <div class="mb-8 text-center">
            <a href="{{ url('/') }}" class="inline-flex flex-col items-center gap-2">
                <!-- Sukha Logo -->
                <div class="w-16 h-16 flex items-center justify-center">
                    <img src="{{ asset('favicon.png') }}?v=4" alt="Sukha" class="w-16 h-16 object-contain rounded-2xl shadow-sm">
                </div>
                <div>
                    <span class="font-playfair text-3xl font-bold" style="color: #2C3E35;">Sukha</span>
                    <p class="text-xs font-medium tracking-widest uppercase mt-0.5" style="color: #7D9B76;">Tu espacio de calma</p>
                </div>
            </a>
        </div>


        <!-- Card -->
        <div class="w-full max-w-md rounded-3xl shadow-xl overflow-hidden" style="background-color: #FAF7F0; border: 1px solid #D4E5D2;">
            <div class="h-1.5 w-full" style="background: linear-gradient(to right, #7D9B76, #B5C9B3, #C4856A);"></div>
            <div class="px-8 py-8">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer note -->
        <p class="mt-6 text-xs text-center" style="color: #6B7B6E;">
            Sukha es una herramienta de bienestar emocional preventivo.<br>
            <strong>No reemplaza la atención psicológica profesional.</strong>
        </p>
    </div>
</body>
</html>
