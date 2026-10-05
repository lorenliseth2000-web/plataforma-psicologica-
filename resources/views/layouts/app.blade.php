<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Sukha — @yield('title', 'Tu espacio de calma')</title>

        <!-- Favicon Permanente -->
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon.png') }}?v=4">
        <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}?v=4">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v=4">
        <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}?v=4">
        <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=4">

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

        <!-- Scripts & Styles via Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --sage: #7D9B76;
                --sage-light: #8FAF88;
                --sage-muted: #B5C9B3;
                --sage-pale: #D4E5D2;
                --sage-ghost: #EAF2E9;
                --ivory: #FAF7F0;
                --ivory-warm: #F5F0E8;
                --ivory-deep: #EDE8DC;
                --terracotta: #C4856A;
                --text-dark: #2C3E35;
                --text-mid: #4A6A55;
                --text-muted: #6B7B6E;
            }
            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                background-color: var(--ivory);
                color: var(--text-dark);
            }
            .font-playfair { font-family: 'Playfair Display', Georgia, serif; }
            [x-cloak] { display: none !important; }
            /* Custom scrollbar */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: var(--ivory-warm); }
            ::-webkit-scrollbar-thumb { background: var(--sage-muted); border-radius: 10px; }
        </style>
        @stack('styles')
    </head>
    <body class="antialiased min-h-screen flex flex-col" style="background-color: {{ !empty($hideChrome) ? '#1a2420' : 'var(--ivory)' }};">

        @unless(!empty($hideChrome))
        <!-- Disclaimer Banner -->
        <aside aria-label="Aviso Legal" style="background-color: #2C3E35; color: #B5C9B3;">
            <div class="max-w-7xl mx-auto px-4 py-2 flex flex-wrap items-center justify-center gap-2 text-xs">
                <span class="flex items-center gap-1.5 font-semibold" style="color: #8FAF88;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#8FAF88" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8C8 10 5.9 16.17 3.82 19.99a1 1 0 001.74.99C7 19 9 17 12 17c3 0 5 2 7 2a4 4 0 000-8z"/><path d="M12 17V7"/></svg>
                    Sukha — Orientación Preventiva:
                </span>
                <span>Esta plataforma acompaña la gestión de ansiedad y estrés leves/moderados.</span>
                <strong style="color: #D4E5D2;">No sustituye diagnóstico ni atención clínica profesional.</strong>
                <a href="{{ route('routes.index') }}" class="underline ml-1" style="color: #8FAF88;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#8FAF88'">Líneas de ayuda →</a>
            </div>
        </aside>

        @include('layouts.navigation')
        @endunless

        <!-- Flash Messages -->
        @if (session('status') || session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full" x-cloak>
                <div class="flex items-center justify-between px-5 py-3.5 rounded-2xl text-sm" style="background-color: #EAF2E9; border: 1px solid #B5C9B3; color: #2C5E28;">
                    <div class="flex items-center gap-2.5">
                        <svg class="w-5 h-5 flex-shrink-0" style="color: #7D9B76;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('status') ?? session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-lg leading-none opacity-60 hover:opacity-100" style="color: #2C5E28;">&times;</button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                 class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full" x-cloak>
                <div class="flex items-center justify-between px-5 py-3.5 rounded-2xl text-sm" style="background-color: #FDF0EC; border: 1px solid #E8C4B8; color: #7A2E1A;">
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="text-lg leading-none">&times;</button>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main class="flex-1">
            @yield('content')
        </main>

        @unless(!empty($hideChrome))
        <!-- Footer -->
        <footer class="mt-auto py-6 border-t" style="background-color: var(--ivory-warm); border-color: var(--sage-pale);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs" style="color: var(--text-muted);">
                <div class="flex items-center gap-1.5">
                    <span class="font-playfair font-semibold" style="color: var(--text-dark);">Sukha</span>
                    <span>— Tu espacio de calma</span>
                </div>
                <span>Sukha no realiza diagnósticos ni reemplaza la atención psicológica profesional.</span>
                <span>&copy; {{ date('Y') }}</span>
            </div>
        </footer>
        @endunless

        @stack('scripts')
    </body>
</html>
