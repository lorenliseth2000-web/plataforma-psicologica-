<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sukha — Tu espacio de calma | Regulación de Ansiedad y Estrés</title>
    <meta name="description" content="Sukha es tu plataforma de bienestar emocional. Técnicas guiadas de psicología para regular la ansiedad y el estrés con apoyo de inteligencia artificial.">

    <!-- Favicon Permanente -->
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon.png') }}?v=4">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}?v=4">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}?v=4">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}?v=4">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=4">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: #FAF7F0; color: #2C3E35; }
        .font-playfair { font-family: 'Playfair Display', Georgia, serif; }
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
            --terracotta-light: #D9957A;
            --text-dark: #2C3E35;
            --text-mid: #4A6A55;
            --text-muted: #6B7B6E;
        }
        .btn-sage {
            background: linear-gradient(135deg, var(--sage) 0%, var(--sage-light) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(125,155,118,0.3);
            transition: all 0.2s;
        }
        .btn-sage:hover { opacity: 0.92; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(125,155,118,0.4); }
        .btn-outline-sage {
            border: 1.5px solid var(--sage);
            color: var(--sage);
            background: transparent;
            transition: all 0.2s;
        }
        .btn-outline-sage:hover { background: var(--sage-ghost); }
        .technique-card {
            background: white;
            border: 1px solid var(--sage-pale);
            border-radius: 20px;
            transition: all 0.25s;
        }
        .technique-card:hover {
            border-color: var(--sage-muted);
            box-shadow: 0 8px 30px rgba(125,155,118,0.15);
            transform: translateY(-3px);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        .float-anim { animation: float 4s ease-in-out infinite; }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        .pulse-soft { animation: pulse-soft 2s ease-in-out infinite; }
    </style>
</head>
<body>

    <!-- Top disclaimer bar -->
    <div style="background-color: var(--sage-ghost); border-bottom: 1px solid var(--sage-pale);">
        <div class="max-w-7xl mx-auto px-4 py-2 flex flex-wrap items-center justify-center gap-2 text-xs" style="color: var(--text-mid);">
            <span class="flex items-center gap-1.5">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8C8 10 5.9 16.17 3.82 19.99a1 1 0 001.74.99C7 19 9 17 12 17c3 0 5 2 7 2a4 4 0 000-8z"/><path d="M12 17V7"/></svg>
                Sukha es una herramienta de bienestar preventivo.
            </span>
            <span class="font-semibold">No sustituye el diagnóstico ni la atención clínica profesional.</span>
            <a href="{{ route('routes.index') }}" class="underline font-medium" style="color: var(--sage);">Líneas de emergencia →</a>
        </div>
    </div>

    <!-- NAVBAR -->
    <header style="background: rgba(250,247,240,0.95); backdrop-filter: blur(12px); border-bottom: 1px solid var(--sage-pale);" class="sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between py-3">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('favicon.png') }}?v=4" alt="Sukha" class="w-10 h-10 object-contain rounded-xl shadow-sm">
                <div>
                    <span class="font-playfair text-2xl font-bold" style="color: var(--text-dark);">Sukha</span>
                    <span class="block text-[10px] font-medium tracking-widest uppercase" style="color: var(--text-muted);">Tu espacio de calma</span>
                </div>
            </a>

            <!-- Nav links -->
            <nav class="hidden md:flex items-center gap-7 text-sm font-medium" style="color: var(--text-muted);">
                <a href="#como-funciona" class="hover:text-sage transition-colors" style="--sage: #7D9B76;" onmouseover="this.style.color='#7D9B76'" onmouseout="this.style.color='#6B7B6E'">¿Cómo funciona?</a>
                <a href="#tecnicas" onmouseover="this.style.color='#7D9B76'" onmouseout="this.style.color='#6B7B6E'">Técnicas</a>
                <a href="#bienestar" onmouseover="this.style.color='#7D9B76'" onmouseout="this.style.color='#6B7B6E'">Ansiedad & Estrés</a>
                <a href="#emergencias" style="color: #C4856A; font-weight: 600;" class="flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full pulse-soft" style="background:#C4856A;display:inline-block;"></span>
                    Emergencias
                </a>
            </nav>

            <!-- CTA buttons -->
            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-sage px-5 py-2.5 rounded-xl font-semibold text-sm">
                        Mi Espacio →
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-outline-sage px-4 py-2 rounded-xl text-sm font-medium">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}" class="btn-sage px-5 py-2.5 rounded-xl font-semibold text-sm">
                        Comenzar gratis
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- ========== HERO SECTION ========== -->
    <section style="background: linear-gradient(160deg, #EAF2E9 0%, #FAF7F0 50%, #F5EFE6 100%);" class="relative overflow-hidden pt-16 pb-24 lg:pt-24 lg:pb-32">

        <!-- Decorative background circles -->
        <div class="absolute top-0 right-0 w-96 h-96 rounded-full opacity-20 pointer-events-none" style="background: radial-gradient(circle, #B5C9B3, transparent); transform: translate(30%, -30%);"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 rounded-full opacity-15 pointer-events-none" style="background: radial-gradient(circle, #D4E5D2, transparent); transform: translate(-30%, 30%);"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                <!-- Text column -->
                <div class="space-y-6 text-center lg:text-left">
                    <div class="flex flex-wrap items-center gap-2 justify-center lg:justify-start">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold" style="background: #D4E5D2; color: #3A6B34;">
                            <span class="w-2 h-2 rounded-full" style="background: #7D9B76;"></span>
                            Bienestar Emocional con Propósito
                        </div>
                    </div>

                    <h1 class="font-playfair text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight" style="color: var(--text-dark);">
                        Encuentra tu<br>
                        <em class="not-italic" style="color: var(--sage);">calma interior</em>
                    </h1>

                    <p class="text-lg leading-relaxed" style="color: var(--text-mid); max-width: 480px;">
                        Sukha te acompaña con técnicas psicológicas guiadas, seguimiento emocional diario e inteligencia artificial ética para gestionar la ansiedad y el estrés.
                    </p>

                    <!-- Trust badges -->
                    <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                        <span class="px-3 py-1.5 rounded-lg text-xs font-medium" style="background: white; border: 1px solid var(--sage-pale); color: var(--text-mid);">✓ 14+ Técnicas Clínicas</span>
                        <span class="px-3 py-1.5 rounded-lg text-xs font-medium" style="background: white; border: 1px solid var(--sage-pale); color: var(--text-mid);">✓ IA con Enfoque Ético</span>
                        <span class="px-3 py-1.5 rounded-lg text-xs font-medium" style="background: white; border: 1px solid var(--sage-pale); color: var(--text-mid);">✓ Seguimiento Diario</span>
                        <span class="px-3 py-1.5 rounded-lg text-xs font-medium" style="background: white; border: 1px solid var(--sage-pale); color: var(--text-mid);">✓ 100% Gratuito</span>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn-sage px-8 py-4 rounded-2xl font-semibold text-base text-center">
                                Ir a mi espacio de calma →
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn-sage px-8 py-4 rounded-2xl font-semibold text-base text-center">
                                Comenzar mi viaje
                            </a>
                            <a href="{{ route('login') }}" class="btn-outline-sage px-8 py-4 rounded-2xl font-semibold text-base text-center">
                                Ya tengo cuenta
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Hero illustration -->
                <div class="flex items-center justify-center float-anim">
                    <svg width="480" height="420" viewBox="0 0 480 420" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Background circle -->
                        <circle cx="240" cy="210" r="180" fill="#EAF2E9" opacity="0.6"/>
                        <circle cx="240" cy="210" r="150" fill="#D4E5D2" opacity="0.4"/>

                        <!-- Seated person - body -->
                        <!-- Legs (lotus position) -->
                        <ellipse cx="200" cy="310" rx="50" ry="18" fill="#B5C9B3" opacity="0.7"/>
                        <ellipse cx="280" cy="310" rx="50" ry="18" fill="#B5C9B3" opacity="0.7"/>
                        <ellipse cx="240" cy="318" rx="65" ry="14" fill="#8FAF88" opacity="0.5"/>

                        <!-- Torso -->
                        <path d="M210 260 Q240 250 270 260 L265 315 Q240 325 215 315 Z" fill="#7D9B76"/>

                        <!-- Clothes/meditation robe -->
                        <path d="M208 268 Q185 280 180 305 Q200 320 240 322 Q280 320 300 305 Q295 280 272 268 Q260 275 240 273 Q220 275 208 268Z" fill="#8FAF88" opacity="0.8"/>

                        <!-- Neck -->
                        <rect x="232" y="240" width="16" height="22" rx="8" fill="#C4856A" opacity="0.8"/>

                        <!-- Head -->
                        <ellipse cx="240" cy="225" rx="30" ry="32" fill="#C4856A" opacity="0.85"/>

                        <!-- Hair -->
                        <path d="M212 215 Q215 185 240 182 Q265 185 268 215 Q260 205 240 203 Q220 205 212 215Z" fill="#4A3728"/>
                        <!-- Bun -->
                        <circle cx="240" cy="187" r="10" fill="#4A3728"/>

                        <!-- Closed peaceful eyes -->
                        <path d="M228 222 Q232 220 236 222" stroke="#2C3E35" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                        <path d="M244 222 Q248 220 252 222" stroke="#2C3E35" stroke-width="1.5" fill="none" stroke-linecap="round"/>

                        <!-- Peaceful smile -->
                        <path d="M234 232 Q240 237 246 232" stroke="#2C3E35" stroke-width="1.5" fill="none" stroke-linecap="round"/>

                        <!-- Arms in meditation mudra -->
                        <!-- Left arm -->
                        <path d="M215 270 Q190 285 185 305" stroke="#C4856A" stroke-width="12" stroke-linecap="round" fill="none" opacity="0.85"/>
                        <!-- Right arm -->
                        <path d="M265 270 Q290 285 295 305" stroke="#C4856A" stroke-width="12" stroke-linecap="round" fill="none" opacity="0.85"/>
                        <!-- Hands -->
                        <circle cx="183" cy="307" r="8" fill="#C4856A" opacity="0.85"/>
                        <circle cx="297" cy="307" r="8" fill="#C4856A" opacity="0.85"/>

                        <!-- Decorative plants around person -->
                        <!-- Left plant -->
                        <path d="M140 360 Q130 330 115 310" stroke="#7D9B76" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <path d="M140 340 Q125 325 120 305" stroke="#7D9B76" stroke-width="2" fill="none" stroke-linecap="round"/>
                        <ellipse cx="112" cy="307" rx="15" ry="10" fill="#8FAF88" opacity="0.8" transform="rotate(-20, 112, 307)"/>
                        <ellipse cx="118" cy="302" rx="12" ry="8" fill="#7D9B76" opacity="0.9" transform="rotate(10, 118, 302)"/>
                        <ellipse cx="122" cy="297" rx="10" ry="7" fill="#B5C9B3" opacity="0.8" transform="rotate(-5, 122, 297)"/>

                        <!-- Right plant -->
                        <path d="M340 360 Q350 330 365 310" stroke="#7D9B76" stroke-width="3" fill="none" stroke-linecap="round"/>
                        <ellipse cx="368" cy="307" rx="15" ry="10" fill="#8FAF88" opacity="0.8" transform="rotate(20, 368, 307)"/>
                        <ellipse cx="362" cy="302" rx="12" ry="8" fill="#7D9B76" opacity="0.9" transform="rotate(-10, 362, 302)"/>
                        <ellipse cx="358" cy="297" rx="10" ry="7" fill="#B5C9B3" opacity="0.8" transform="rotate(5, 358, 297)"/>

                        <!-- Floating lotus flowers -->
                        <g transform="translate(150, 160) rotate(-15)">
                            <path d="M0 0 C-8 -15 -5 -30 0 -38 C5 -30 8 -15 0 0Z" fill="#B5C9B3" opacity="0.9"/>
                            <path d="M0 0 C-15 -8 -25 -18 -20 -35 C-10 -22 -5 -12 0 0Z" fill="#8FAF88" opacity="0.8"/>
                            <path d="M0 0 C15 -8 25 -18 20 -35 C10 -22 5 -12 0 0Z" fill="#8FAF88" opacity="0.8"/>
                        </g>

                        <g transform="translate(330, 150) rotate(10)">
                            <path d="M0 0 C-8 -15 -5 -30 0 -38 C5 -30 8 -15 0 0Z" fill="#D4E5D2" opacity="0.9"/>
                            <path d="M0 0 C-15 -8 -25 -18 -20 -35 C-10 -22 -5 -12 0 0Z" fill="#B5C9B3" opacity="0.8"/>
                            <path d="M0 0 C15 -8 25 -18 20 -35 C10 -22 5 -12 0 0Z" fill="#B5C9B3" opacity="0.8"/>
                        </g>

                        <!-- Breathing rings around person (showing breath) -->
                        <circle cx="240" cy="240" r="100" stroke="#7D9B76" stroke-width="1" fill="none" opacity="0.2" stroke-dasharray="5 3"/>
                        <circle cx="240" cy="240" r="125" stroke="#8FAF88" stroke-width="0.5" fill="none" opacity="0.15" stroke-dasharray="3 4"/>

                        <!-- Small sparkle/star elements -->
                        <circle cx="170" cy="190" r="3" fill="#C4856A" opacity="0.6"/>
                        <circle cx="310" cy="185" r="2.5" fill="#7D9B76" opacity="0.7"/>
                        <circle cx="155" cy="240" r="2" fill="#8FAF88" opacity="0.8"/>
                        <circle cx="325" cy="235" r="3.5" fill="#B5C9B3" opacity="0.6"/>

                        <!-- Ground / mat -->
                        <ellipse cx="240" cy="345" rx="90" ry="12" fill="#D4E5D2" opacity="0.6"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== HOW IT WORKS ========== -->
    <section id="como-funciona" class="py-20" style="background: white;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-xs font-semibold uppercase tracking-widest" style="color: var(--sage);">Simple y efectivo</span>
                <h2 class="font-playfair text-3xl sm:text-4xl font-bold mt-2" style="color: var(--text-dark);">¿Cómo funciona Sukha?</h2>
                <p class="mt-3 text-base max-w-xl mx-auto" style="color: var(--text-muted);">Un camino claro hacia el bienestar emocional en tres pasos sencillos.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center p-8 rounded-3xl relative" style="background: var(--ivory-warm);">
                    <div class="w-14 h-14 rounded-2xl mx-auto mb-5 flex items-center justify-center text-2xl font-playfair font-bold" style="background: var(--sage-ghost); color: var(--sage);">1</div>
                    <svg class="mx-auto mb-4 w-20 h-20" viewBox="0 0 80 80" fill="none">
                        <rect x="15" y="10" width="50" height="60" rx="8" fill="#EAF2E9"/>
                        <rect x="22" y="20" width="36" height="4" rx="2" fill="#8FAF88"/>
                        <rect x="22" y="28" width="28" height="3" rx="1.5" fill="#B5C9B3"/>
                        <rect x="22" y="34" width="32" height="3" rx="1.5" fill="#B5C9B3"/>
                        <circle cx="25" cy="50" r="4" fill="#7D9B76" opacity="0.4"/>
                        <circle cx="38" cy="50" r="4" fill="#7D9B76" opacity="0.7"/>
                        <circle cx="51" cy="50" r="4" fill="#7D9B76"/>
                    </svg>
                    <h3 class="font-playfair text-xl font-semibold mb-2" style="color: var(--text-dark);">Evalúa tu estado</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--text-muted);">Responde nuestro cuestionario clínico de 10 preguntas para identificar tus niveles de ansiedad y estrés.</p>
                </div>
                <!-- Step 2 -->
                <div class="text-center p-8 rounded-3xl relative" style="background: var(--sage-ghost);">
                    <div class="w-14 h-14 rounded-2xl mx-auto mb-5 flex items-center justify-center text-2xl font-playfair font-bold" style="background: white; color: var(--sage);">2</div>
                    <svg class="mx-auto mb-4 w-20 h-20" viewBox="0 0 80 80" fill="none">
                        <circle cx="40" cy="40" r="25" fill="#D4E5D2" opacity="0.5"/>
                        <circle cx="40" cy="40" r="18" fill="#B5C9B3" opacity="0.4"/>
                        <circle cx="40" cy="40" r="11" fill="#7D9B76" opacity="0.6"/>
                        <path d="M40 22 Q40 35 40 40" stroke="#2C3E35" stroke-width="2" stroke-linecap="round"/>
                        <path d="M40 40 Q48 34 52 28" stroke="#C4856A" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="40" cy="40" r="2" fill="#2C3E35"/>
                    </svg>
                    <h3 class="font-playfair text-xl font-semibold mb-2" style="color: var(--text-dark);">Practica técnicas guiadas</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--text-muted);">Accede a 14+ técnicas de psicología con animaciones interactivas, respiración guiada y sonidos relajantes.</p>
                </div>
                <!-- Step 3 -->
                <div class="text-center p-8 rounded-3xl relative" style="background: var(--ivory-warm);">
                    <div class="w-14 h-14 rounded-2xl mx-auto mb-5 flex items-center justify-center text-2xl font-playfair font-bold" style="background: var(--sage-ghost); color: var(--sage);">3</div>
                    <svg class="mx-auto mb-4 w-20 h-20" viewBox="0 0 80 80" fill="none">
                        <rect x="10" y="45" width="12" height="25" rx="3" fill="#B5C9B3"/>
                        <rect x="26" y="35" width="12" height="35" rx="3" fill="#8FAF88"/>
                        <rect x="42" y="25" width="12" height="45" rx="3" fill="#7D9B76"/>
                        <rect x="58" y="15" width="12" height="55" rx="3" fill="#5A7A53"/>
                        <path d="M16 44 Q32 30 48 22 Q64 14 70 12" stroke="#C4856A" stroke-width="2" stroke-linecap="round" fill="none" stroke-dasharray="4 2"/>
                    </svg>
                    <h3 class="font-playfair text-xl font-semibold mb-2" style="color: var(--text-dark);">Observa tu progreso</h3>
                    <p class="text-sm leading-relaxed" style="color: var(--text-muted);">Registra tu estado emocional diariamente y visualiza tu evolución con gráficas claras.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== TECHNIQUES SECTION ========== -->
    <section id="tecnicas" class="py-20" style="background: var(--ivory-warm);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-xs font-semibold uppercase tracking-widest" style="color: var(--sage);">Basadas en evidencia</span>
                <h2 class="font-playfair text-3xl sm:text-4xl font-bold mt-2" style="color: var(--text-dark);">Técnicas Psicológicas Guiadas</h2>
                <p class="mt-3 text-base max-w-xl mx-auto" style="color: var(--text-muted);">Herramientas clínicas respaldadas por la psicología cognitivo-conductual, el mindfulness y la neurociencia.</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <!-- Technique 1: Breathing -->
                <div class="technique-card p-6">
                    <div class="w-14 h-14 rounded-2xl mb-4 flex items-center justify-center" style="background: var(--sage-ghost);">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <circle cx="16" cy="16" r="10" stroke="#7D9B76" stroke-width="2" fill="none"/>
                            <circle cx="16" cy="16" r="6" stroke="#8FAF88" stroke-width="1.5" fill="none"/>
                            <circle cx="16" cy="16" r="2" fill="#7D9B76"/>
                            <path d="M16 6 L16 4" stroke="#7D9B76" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M16 26 L16 28" stroke="#7D9B76" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M6 16 L4 16" stroke="#7D9B76" stroke-width="1.5" stroke-linecap="round"/>
                            <path d="M26 16 L28 16" stroke="#7D9B76" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-base mb-1" style="color: var(--text-dark);">Respiración Guiada</h3>
                    <p class="text-xs leading-relaxed" style="color: var(--text-muted);">Respiración diafragmática, 4-7-8 y en caja para calmar el sistema nervioso en minutos.</p>
                    <span class="mt-3 inline-block text-xs font-medium px-2.5 py-1 rounded-full" style="background: var(--sage-ghost); color: var(--sage);">Para Ansiedad</span>
                </div>

                <!-- Technique 2: Grounding -->
                <div class="technique-card p-6">
                    <div class="w-14 h-14 rounded-2xl mb-4 flex items-center justify-center" style="background: #FFF5F0;">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <path d="M8 24 L16 8 L24 24 Z" stroke="#C4856A" stroke-width="2" fill="none" stroke-linejoin="round"/>
                            <path d="M5 28 L27 28" stroke="#C4856A" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="16" cy="18" r="3" fill="#D9957A" opacity="0.6"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-base mb-1" style="color: var(--text-dark);">Grounding 5-4-3-2-1</h3>
                    <p class="text-xs leading-relaxed" style="color: var(--text-muted);">Técnica de anclaje sensorial para volver al presente cuando la ansiedad te aleja de aquí y ahora.</p>
                    <span class="mt-3 inline-block text-xs font-medium px-2.5 py-1 rounded-full" style="background: #FFF5F0; color: #C4856A;">Para Ansiedad</span>
                </div>

                <!-- Technique 3: Muscle Relaxation -->
                <div class="technique-card p-6">
                    <div class="w-14 h-14 rounded-2xl mb-4 flex items-center justify-center" style="background: var(--sage-ghost);">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <path d="M16 4 C16 4 22 10 22 18 C22 24 19 28 16 28 C13 28 10 24 10 18 C10 10 16 4 16 4Z" stroke="#7D9B76" stroke-width="2" fill="#D4E5D2" opacity="0.5"/>
                            <path d="M12 18 C14 16 18 16 20 18" stroke="#7D9B76" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-base mb-1" style="color: var(--text-dark);">Relajación Muscular</h3>
                    <p class="text-xs leading-relaxed" style="color: var(--text-muted);">Técnica de Jacobson: tensión y liberación progresiva de grupos musculares para eliminar el estrés acumulado.</p>
                    <span class="mt-3 inline-block text-xs font-medium px-2.5 py-1 rounded-full" style="background: var(--sage-ghost); color: var(--sage);">Para Estrés</span>
                </div>

                <!-- Technique 4: Mindfulness -->
                <div class="technique-card p-6">
                    <div class="w-14 h-14 rounded-2xl mb-4 flex items-center justify-center" style="background: var(--ivory-deep);">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <circle cx="16" cy="16" r="12" fill="#EDE8DC" opacity="0.6"/>
                            <path d="M16 8 C20 12 20 20 16 24 C12 20 12 12 16 8Z" fill="#8FAF88" opacity="0.9"/>
                            <path d="M8 16 C12 12 20 12 24 16 C20 20 12 20 8 16Z" fill="#B5C9B3" opacity="0.7"/>
                            <circle cx="16" cy="16" r="2.5" fill="#7D9B76"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-base mb-1" style="color: var(--text-dark);">Mindfulness</h3>
                    <p class="text-xs leading-relaxed" style="color: var(--text-muted);">Atención plena y escaneo corporal para desarrollar la conciencia del momento presente sin juicios.</p>
                    <span class="mt-3 inline-block text-xs font-medium px-2.5 py-1 rounded-full" style="background: var(--ivory-deep); color: #6B5A3E;">Para Estrés</span>
                </div>
            </div>

            <div class="text-center mt-10">
                @auth
                    <a href="{{ route('techniques.index') }}" class="btn-sage inline-block px-8 py-4 rounded-2xl font-semibold text-base">
                        Explorar las 14+ técnicas →
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-sage inline-block px-8 py-4 rounded-2xl font-semibold text-base">
                        Acceder a todas las técnicas gratis →
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- ========== ANXIETY vs STRESS EDUCATION ========== -->
    <section id="bienestar" class="py-20" style="background: white;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-14">
                <span class="text-xs font-semibold uppercase tracking-widest" style="color: var(--sage);">Psicoeducación</span>
                <h2 class="font-playfair text-3xl sm:text-4xl font-bold mt-2" style="color: var(--text-dark);">¿Ansiedad o Estrés? Conoce la diferencia</h2>
            </div>
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Anxiety -->
                <div class="p-8 rounded-3xl" style="background: linear-gradient(135deg, #EAF2E9 0%, #F5F0E8 100%); border: 1px solid #D4E5D2;">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--sage);">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9.5 2C8 2 7 3 7 4.5v1.17A4 4 0 004 9.5v.5a3.5 3.5 0 001 6.83V17a4 4 0 004 4h6a4 4 0 004-4v-.17A3.5 3.5 0 0020 10v-.5a4 4 0 00-3-3.83V4.5C17 3 16 2 14.5 2a2.5 2.5 0 00-2.5 2 2.5 2.5 0 00-2.5-2z"/>
                            </svg>
                        </div>
                        <h3 class="font-playfair text-xl font-bold" style="color: var(--text-dark);">Ansiedad</h3>
                    </div>
                    <p class="text-sm leading-relaxed mb-4" style="color: var(--text-mid);">La ansiedad es una respuesta emocional ante amenazas percibidas, reales o imaginarias, que activa el sistema nervioso autónomo preparando al cuerpo para "luchar o huir".</p>
                    <ul class="space-y-2 text-sm" style="color: var(--text-mid);">
                        <li class="flex items-start gap-2"><span style="color: var(--sage);">●</span> Preocupación persistente o excesiva</li>
                        <li class="flex items-start gap-2"><span style="color: var(--sage);">●</span> Tensión muscular y taquicardia</li>
                        <li class="flex items-start gap-2"><span style="color: var(--sage);">●</span> Dificultad para concentrarse</li>
                        <li class="flex items-start gap-2"><span style="color: var(--sage);">●</span> Sensación de peligro inminente</li>
                    </ul>
                    <div class="mt-4 px-4 py-2 rounded-xl text-xs" style="background: white; border: 1px solid var(--sage-pale); color: var(--text-mid);">
                        <strong>Técnicas Sukha recomendadas:</strong> Respiración 4-7-8, Grounding 5-4-3-2-1, Lugar Seguro
                    </div>
                </div>

                <!-- Stress -->
                <div class="p-8 rounded-3xl" style="background: linear-gradient(135deg, #FFF5F0 0%, #F5F0E8 100%); border: 1px solid #E8C4B8;">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: #C4856A;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                            </svg>
                        </div>
                        <h3 class="font-playfair text-xl font-bold" style="color: var(--text-dark);">Estrés</h3>
                    </div>
                    <p class="text-sm leading-relaxed mb-4" style="color: var(--text-mid);">El estrés es la respuesta fisiológica y psicológica ante demandas que superan los recursos percibidos de la persona. Es situacional y generalmente tiene una causa identificable.</p>
                    <ul class="space-y-2 text-sm" style="color: var(--text-mid);">
                        <li class="flex items-start gap-2"><span style="color: #C4856A;">●</span> Agotamiento físico y mental</li>
                        <li class="flex items-start gap-2"><span style="color: #C4856A;">●</span> Dificultad para descansar</li>
                        <li class="flex items-start gap-2"><span style="color: #C4856A;">●</span> Irritabilidad y baja tolerancia</li>
                        <li class="flex items-start gap-2"><span style="color: #C4856A;">●</span> Disminución del rendimiento</li>
                    </ul>
                    <div class="mt-4 px-4 py-2 rounded-xl text-xs" style="background: white; border: 1px solid #E8C4B8; color: var(--text-mid);">
                        <strong>Técnicas Sukha recomendadas:</strong> Relajación de Jacobson, Body Scan, Técnica STOP
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== EMERGENCY SECTION ========== -->
    <section id="emergencias" class="py-14">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="p-8 rounded-3xl text-center" style="background: linear-gradient(135deg, #FDF0EC 0%, #F5EDE8 100%); border: 2px solid #E8C4B8;">
                <div class="w-14 h-14 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: #C4856A;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92z"/>
                    </svg>
                </div>
                <h2 class="font-playfair text-2xl font-bold mb-2" style="color: #7A2E1A;">¿Estás en una situación de crisis?</h2>
                <p class="text-sm mb-6" style="color: #9B4D35;">Si sientes que tú o alguien más está en peligro, no esperes. Llama de inmediato a las líneas de atención:</p>
                <div class="grid sm:grid-cols-3 gap-4">
                    <a href="tel:106" class="flex flex-col items-center p-4 rounded-2xl font-bold text-white transition-all hover:opacity-90" style="background: #C4856A;">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-2" style="background: rgba(255,255,255,0.2);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92z"/>
                            </svg>
                        </div>
                        <span class="text-lg">106</span>
                        <span class="text-xs font-normal opacity-90">Línea de Salud Mental</span>
                    </a>
                    <a href="tel:192" class="flex flex-col items-center p-4 rounded-2xl font-bold text-white transition-all hover:opacity-90" style="background: #C4856A;">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-2" style="background: rgba(255,255,255,0.2);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                            </svg>
                        </div>
                        <span class="text-lg">192</span>
                        <span class="text-xs font-normal opacity-90">Urgencias / SAMU</span>
                    </a>
                    <a href="tel:123" class="flex flex-col items-center p-4 rounded-2xl font-bold text-white transition-all hover:opacity-90" style="background: #9B4D35;">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-2" style="background: rgba(255,255,255,0.2);">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                        </div>
                        <span class="text-lg">123</span>
                        <span class="text-xs font-normal opacity-90">Policía / Emergencias</span>
                    </a>
                </div>
                <p class="mt-4 text-xs" style="color: #9B4D35;">Estas líneas son <strong>gratuitas, confidenciales y disponibles 24/7</strong></p>
                <a href="{{ route('routes.index') }}" class="mt-4 inline-block text-sm font-medium underline" style="color: #C4856A;">Ver directorio completo de rutas de atención →</a>
            </div>
        </div>
    </section>

    <!-- ========== FOOTER ========== -->
    <footer style="background: #2C3E35; color: #B5C9B3;" class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-10 mb-10">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <img src="{{ asset('favicon.png') }}?v=4" alt="Sukha" class="w-8 h-8 object-contain rounded-lg">
                        <span class="font-playfair text-xl font-bold text-white">Sukha</span>
                    </div>
                    <p class="text-sm italic" style="color: #8FAF88;">"Sukha" — en sánscrito:<br>facilidad, felicidad, claridad</p>
                    <p class="text-xs mt-3 leading-relaxed" style="color: #6B7B6E;">Herramienta preventiva de bienestar emocional. No realiza diagnósticos ni reemplaza la atención psicológica profesional.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-white text-sm mb-3">Módulos</h4>
                    <ul class="space-y-2 text-sm">
                        @auth
                            <li><a href="{{ route('assessments.create') }}" style="color: #8FAF88;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#8FAF88'">Tamizaje Clínico</a></li>
                            <li><a href="{{ route('techniques.index') }}" style="color: #8FAF88;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#8FAF88'">Técnicas Guiadas</a></li>
                            <li><a href="{{ route('emotional-logs.create') }}" style="color: #8FAF88;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#8FAF88'">Diario Emocional</a></li>
                            <li><a href="{{ route('routes.index') }}" style="color: #8FAF88;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#8FAF88'">Rutas de Atención</a></li>
                        @else
                            <li><a href="{{ route('login') }}" style="color: #8FAF88;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#8FAF88'">Iniciar Sesión</a></li>
                            <li><a href="{{ route('register') }}" style="color: #8FAF88;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#8FAF88'">Registrarse</a></li>
                        @endauth
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white text-sm mb-3">Emergencias</h4>
                    <ul class="space-y-1.5 text-sm" style="color: #B5C9B3;">
                        <li class="flex items-center gap-2">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#B5C9B3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92z"/></svg>
                            Línea 106 — Salud Mental
                        </li>
                        <li class="flex items-center gap-2">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#B5C9B3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                            Línea 192 — Urgencias
                        </li>
                        <li class="flex items-center gap-2">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#B5C9B3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Línea 123 — Policía / Emergencias
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs" style="border-color: #3A5445; color: #6B7B6E;">
                <p>&copy; {{ date('Y') }} Sukha — Todos los derechos reservados.</p>
                <p>Proyecto académico. No es un servicio médico certificado.</p>
            </div>
        </div>
    </footer>

</body>
</html>
