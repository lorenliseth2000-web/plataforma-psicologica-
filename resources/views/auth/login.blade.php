<x-guest-layout>
    <div class="w-full">

        {{-- Título de bienvenida --}}
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold mt-4" style="color: #2C3E35; font-family: 'Playfair Display', Georgia, serif;">
                Bienvenido/a de vuelta
            </h2>
            <p class="text-sm mt-1" style="color: #6B7B6E;">Tu espacio de calma te espera</p>
        </div>

        <!-- Mensajes de estado -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold mb-1.5" style="color: #4A6A55;">
                    Correo electrónico
                </label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autocomplete="username"
                       placeholder="tu@correo.com"
                       class="w-full px-4 py-3 rounded-xl text-sm border transition outline-none"
                       style="border-color: #D4E5D2; background: #FAFFF9; color: #2C3E35;"
                       onfocus="this.style.borderColor='#7D9B76'; this.style.boxShadow='0 0 0 3px rgba(125,155,118,0.15)';"
                       onblur="this.style.borderColor='#D4E5D2'; this.style.boxShadow='none';">
                <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
            </div>

            <!-- Contraseña -->
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label for="password" class="block text-xs font-semibold" style="color: #4A6A55;">
                        Contraseña
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-xs transition"
                           style="color: #8FAF88;"
                           onmouseover="this.style.color='#7D9B76'"
                           onmouseout="this.style.color='#8FAF88'">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full px-4 py-3 rounded-xl text-sm border transition outline-none"
                       style="border-color: #D4E5D2; background: #FAFFF9; color: #2C3E35;"
                       onfocus="this.style.borderColor='#7D9B76'; this.style.boxShadow='0 0 0 3px rgba(125,155,118,0.15)';"
                       onblur="this.style.borderColor='#D4E5D2'; this.style.boxShadow='none';">
                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
            </div>

            <!-- Recordarme -->
            <div class="flex items-center gap-2">
                <input id="remember_me"
                       type="checkbox"
                       name="remember"
                       class="w-4 h-4 rounded"
                       style="accent-color: #7D9B76;">
                <label for="remember_me" class="text-xs" style="color: #6B7B6E;">
                    Mantener sesión iniciada
                </label>
            </div>

            <!-- Botón principal -->
            <button type="submit"
                    class="w-full py-3.5 rounded-xl font-bold text-sm text-white transition duration-200"
                    style="background: linear-gradient(135deg, #7D9B76, #8FAF88);"
                    onmouseover="this.style.opacity='0.9'; this.style.transform='translateY(-1px)';"
                    onmouseout="this.style.opacity='1'; this.style.transform='translateY(0)';">
                Iniciar sesión
            </button>

            <!-- Registro -->
            @if (Route::has('register'))
                <p class="text-center text-xs" style="color: #6B7B6E;">
                    ¿Aún no tienes cuenta?
                    <a href="{{ route('register') }}"
                       class="font-semibold ml-1 transition"
                       style="color: #7D9B76;"
                       onmouseover="this.style.color='#4A6A55'"
                       onmouseout="this.style.color='#7D9B76'">
                        Crear cuenta en Sukha
                    </a>
                </p>
            @endif
        </form>
    </div>
</x-guest-layout>
