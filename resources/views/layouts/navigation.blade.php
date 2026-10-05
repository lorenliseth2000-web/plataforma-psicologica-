<nav x-data="{ open: false }" style="background: rgba(250,247,240,0.97); backdrop-filter: blur(12px); border-bottom: 1px solid #D4E5D2;" class="sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Logo & Nav Links -->
            <div class="flex items-center">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 mr-8">
                    <img src="{{ asset('favicon.png') }}?v=4" alt="Sukha" class="w-9 h-9 object-contain rounded-xl shadow-sm">
                    <div>
                        <span class="font-playfair text-xl font-bold" style="color: #2C3E35; font-family: 'Playfair Display', Georgia, serif;">Sukha</span>
                        <span class="block text-[10px] tracking-widest uppercase" style="color: #7D9B76; line-height: 1;">Tu espacio de calma</span>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <div class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'font-semibold' : '' }}"
                       style="{{ request()->routeIs('dashboard') ? 'background: #EAF2E9; color: #7D9B76;' : 'color: #6B7B6E;' }}"
                       onmouseover="if(!this.classList.contains('active')) this.style.background='#EAF2E9'"
                       onmouseout="if(!{{ request()->routeIs('dashboard') ? 'true' : 'false' }}) this.style.background='transparent'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Mi Espacio
                    </a>

                    <a href="{{ route('assessments.index') }}"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium transition-all"
                       style="{{ request()->routeIs('assessments.*') ? 'background: #EAF2E9; color: #7D9B76; font-weight: 600;' : 'color: #6B7B6E;' }}"
                       onmouseover="this.style.background='#EAF2E9'"
                       onmouseout="{{ request()->routeIs('assessments.*') ? '' : "this.style.background='transparent'" }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Tamizaje
                    </a>

                    <a href="{{ route('techniques.index') }}"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium transition-all"
                       style="{{ request()->routeIs('techniques.*') ? 'background: #EAF2E9; color: #7D9B76; font-weight: 600;' : 'color: #6B7B6E;' }}"
                       onmouseover="this.style.background='#EAF2E9'"
                       onmouseout="{{ request()->routeIs('techniques.*') ? '' : "this.style.background='transparent'" }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        Técnicas
                    </a>

                    <a href="{{ route('emotional-logs.index') }}"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium transition-all"
                       style="{{ request()->routeIs('emotional-logs.*') ? 'background: #EAF2E9; color: #7D9B76; font-weight: 600;' : 'color: #6B7B6E;' }}"
                       onmouseover="this.style.background='#EAF2E9'"
                       onmouseout="{{ request()->routeIs('emotional-logs.*') ? '' : "this.style.background='transparent'" }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Diario
                    </a>

                    <a href="{{ route('routes.index') }}"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium transition-all"
                       style="color: #C4856A; font-weight: 500;"
                       onmouseover="this.style.background='#FFF5F0'"
                       onmouseout="this.style.background='transparent'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Ayuda
                    </a>

                    <a href="{{ route('relaxation.index') }}"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium transition-all"
                       style="{{ request()->routeIs('relaxation.*') ? 'background: #EAF2E9; color: #7D9B76; font-weight: 600;' : 'color: #6B7B6E;' }}"
                       onmouseover="this.style.background='#EAF2E9'"
                       onmouseout="{{ request()->routeIs('relaxation.*') ? '' : "this.style.background='transparent'" }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                        Relajación
                    </a>

                    <a href="{{ route('chatbot.index') }}"
                       class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium transition-all"
                       style="{{ request()->routeIs('chatbot.*') ? 'background: #EAF2E9; color: #7D9B76; font-weight: 600;' : 'color: #6B7B6E;' }}"
                       onmouseover="this.style.background='#EAF2E9'"
                       onmouseout="{{ request()->routeIs('chatbot.*') ? '' : "this.style.background='transparent'" }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        Acompañante
                    </a>

                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-semibold transition-all ml-1"
                           style="{{ request()->routeIs('admin.*') ? 'background: #2C3E35; color: white;' : 'background: #EDE8DC; color: #2C3E35;' }}"
                           onmouseover="this.style.background='#2C3E35'; this.style.color='white'"
                           onmouseout="{{ request()->routeIs('admin.*') ? '' : "this.style.background='#EDE8DC'; this.style.color='#2C3E35'" }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Admin
                        </a>
                    @endif
                </div>
            </div>

            <!-- User Menu -->
            <div class="hidden sm:flex items-center">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium transition-all"
                            style="color: #2C3E35;"
                            onmouseover="this.style.background='#EAF2E9'"
                            onmouseout="this.style.background='transparent'">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white text-xs font-bold" style="background: linear-gradient(135deg, #7D9B76, #8FAF88);">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span>{{ explode(' ', auth()->user()->name)[0] }}</span>
                        <svg class="w-4 h-4 transition-transform" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 mt-2 w-52 rounded-2xl shadow-xl overflow-hidden py-1.5 z-50"
                         style="background: white; border: 1px solid #D4E5D2;">
                        <div class="px-4 py-3 border-b" style="border-color: #EAF2E9;">
                            <p class="text-xs font-bold" style="color: #2C3E35;">{{ auth()->user()->name }}</p>
                            <p class="text-xs" style="color: #6B7B6E;">{{ auth()->user()->email }}</p>
                        </div>                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm transition-all" style="color: #4A6A55;" onmouseover="this.style.background='#EAF2E9'" onmouseout="this.style.background='transparent'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Mi Perfil
                        </a>
                        <a href="{{ route('reminders.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm transition-all" style="color: #4A6A55;" onmouseover="this.style.background='#EAF2E9'" onmouseout="this.style.background='transparent'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            Recordatorios
                        </a>
                        <div class="border-t mx-3 my-1" style="border-color: #EAF2E9;"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm transition-all text-left" style="color: #C4856A;" onmouseover="this.style.background='#FFF5F0'" onmouseout="this.style.background='transparent'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <button @click="open = !open" class="p-2 rounded-xl" style="color: #7D9B76;">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" x-cloak class="sm:hidden border-t" style="border-color: #D4E5D2; background: #FAF7F0;">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm" style="{{ request()->routeIs('dashboard') ? 'background: #EAF2E9; color: #7D9B76; font-weight: 600;' : 'color: #4A6A55;' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Mi Espacio
            </a>
            <a href="{{ route('assessments.index') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm" style="color: #4A6A55;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Tamizaje
            </a>
            <a href="{{ route('techniques.index') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm" style="color: #4A6A55;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                Técnicas
            </a>
            <a href="{{ route('emotional-logs.index') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm" style="color: #4A6A55;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Diario
            </a>
            <a href="{{ route('routes.index') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm" style="color: #C4856A;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Rutas de Ayuda
            </a>
            <a href="{{ route('relaxation.index') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm" style="{{ request()->routeIs('relaxation.*') ? 'background: #EAF2E9; color: #7D9B76; font-weight: 600;' : 'color: #4A6A55;' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                Relajación
            </a>
            <a href="{{ route('chatbot.index') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm" style="{{ request()->routeIs('chatbot.*') ? 'background: #EAF2E9; color: #7D9B76; font-weight: 600;' : 'color: #4A6A55;' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                Acompañante
            </a>
            <a href="{{ route('reminders.index') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm" style="color: #4A6A55;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                Recordatorios
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm" style="color: #4A6A55;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Perfil
            </a>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold" style="background: #EDE8DC; color: #2C3E35;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Admin
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="pt-2 border-t" style="border-color: #D4E5D2;">
                @csrf
                <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm" style="color: #C4856A;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>

</nav>
