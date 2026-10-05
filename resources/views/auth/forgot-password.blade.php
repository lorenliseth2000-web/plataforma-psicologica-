<x-guest-layout>
    <div class="mb-6">
        <h1 class="font-playfair text-2xl font-bold" style="color: #2C3E35;">¿Olvidaste tu contraseña?</h1>
        <p class="text-sm mt-1" style="color: #6B7B6E;">Ingresa tu correo electrónico registrado y te enviaremos un código de seguridad para recuperarla.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color: #2C3E35;">
                Correo electrónico
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="w-full px-4 py-3 rounded-2xl text-sm outline-none transition-all"
                style="background-color: #F5F0E8; border: 1.5px solid #D4E5D2; color: #2C3E35;"
                onfocus="this.style.borderColor='#7D9B76'; this.style.boxShadow='0 0 0 3px rgba(125,155,118,0.15)'"
                onblur="this.style.borderColor='#D4E5D2'; this.style.boxShadow='none'"
                placeholder="tu@correo.com"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <button
            type="submit"
            class="w-full py-3.5 rounded-2xl text-white font-semibold text-sm tracking-wide transition-all hover:opacity-90 active:scale-[0.98] mt-2"
            style="background: linear-gradient(135deg, #7D9B76 0%, #8FAF88 100%); box-shadow: 0 4px 12px rgba(125,155,118,0.35);"
        >
            Enviar código de restablecimiento
        </button>

        <div class="pt-3 text-center text-xs space-y-1.5" style="color: #6B7B6E;">
            <p>
                ¿Tienes un código de 6 dígitos?
                <a href="{{ url('restablecer_password.php') }}" class="font-semibold hover:underline" style="color: #7D9B76;">Restablecer con código</a>
            </p>
            <p>
                <a href="{{ route('login') }}" class="hover:underline" style="color: #8FAF88;">Volver a iniciar sesión</a>
            </p>
        </div>
    </form>
</x-guest-layout>
