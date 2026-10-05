<x-guest-layout>
    <div class="mb-6">
        <h1 class="font-playfair text-2xl font-bold" style="color: #2C3E35;">Crea tu espacio en Sukha</h1>
        <p class="text-sm mt-1" style="color: #6B7B6E;">Regístrate gratis y comienza tu camino hacia el bienestar emocional.</p>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="mb-4 px-4 py-3 rounded-xl text-sm" style="background-color: #FDF0EC; color: #9B3D26; border: 1px solid #E8C4B8;">
            <p class="font-semibold mb-1">Por favor corrige los siguientes errores:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Geolocalización (opcional) -->
        <input type="hidden" id="latitud" name="latitud" value="{{ old('latitud') }}">
        <input type="hidden" id="longitud" name="longitud" value="{{ old('longitud') }}">

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color: #2C3E35;">
                Nombre completo
            </label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                class="w-full px-4 py-3 rounded-2xl text-sm outline-none transition-all"
                style="background-color: #F5F0E8; border: 1.5px solid #D4E5D2; color: #2C3E35;"
                onfocus="this.style.borderColor='#7D9B76'; this.style.boxShadow='0 0 0 3px rgba(125,155,118,0.15)'"
                onblur="this.style.borderColor='#D4E5D2'; this.style.boxShadow='none'"
                placeholder="Tu nombre"
            >
        </div>

        <!-- Email -->
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
                autocomplete="username"
                class="w-full px-4 py-3 rounded-2xl text-sm outline-none transition-all"
                style="background-color: #F5F0E8; border: 1.5px solid #D4E5D2; color: #2C3E35;"
                onfocus="this.style.borderColor='#7D9B76'; this.style.boxShadow='0 0 0 3px rgba(125,155,118,0.15)'"
                onblur="this.style.borderColor='#D4E5D2'; this.style.boxShadow='none'"
                placeholder="tu@correo.com"
            >
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color: #2C3E35;">
                Contraseña
            </label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                class="w-full px-4 py-3 rounded-2xl text-sm outline-none transition-all"
                style="background-color: #F5F0E8; border: 1.5px solid #D4E5D2; color: #2C3E35;"
                onfocus="this.style.borderColor='#7D9B76'; this.style.boxShadow='0 0 0 3px rgba(125,155,118,0.15)'"
                onblur="this.style.borderColor='#D4E5D2'; this.style.boxShadow='none'"
                placeholder="Mínimo 8 caracteres"
            >
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color: #2C3E35;">
                Confirmar contraseña
            </label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="w-full px-4 py-3 rounded-2xl text-sm outline-none transition-all"
                style="background-color: #F5F0E8; border: 1.5px solid #D4E5D2; color: #2C3E35;"
                onfocus="this.style.borderColor='#7D9B76'; this.style.boxShadow='0 0 0 3px rgba(125,155,118,0.15)'"
                onblur="this.style.borderColor='#D4E5D2'; this.style.boxShadow='none'"
                placeholder="Repite tu contraseña"
            >
        </div>

        <!-- Género (para personalizar avatar) -->
        <div>
            <label class="block text-xs font-semibold uppercase tracking-wider mb-2" style="color: #2C3E35;">
                ¿Cómo deseas que te identifique Sukha?
                <span class="text-[10px] font-normal ml-1" style="color: #8FAF88;">(para personalizar tu avatar)</span>
            </label>
            <div class="grid grid-cols-2 gap-2">
                @foreach(['male' => 'Hombre', 'female' => 'Mujer', 'non_binary' => 'No binario', 'prefer_not_to_say' => 'Prefiero no decir'] as $value => $label)
                <label class="flex items-center gap-2 cursor-pointer rounded-2xl px-3 py-2.5 border transition text-sm"
                       style="border-color: #D4E5D2; color: #4A6A55;">
                    <input type="radio" name="gender" value="{{ $value }}"
                           {{ old('gender') === $value ? 'checked' : '' }}
                           class="w-4 h-4" style="accent-color: #7D9B76;">
                    {{ $label }}
                </label>
                @endforeach
            </div>
        </div>

        <!-- Submit -->
        <button
            type="submit"
            class="w-full py-3.5 rounded-2xl text-white font-semibold text-sm tracking-wide transition-all hover:opacity-90 active:scale-[0.98] mt-2"
            style="background: linear-gradient(135deg, #7D9B76 0%, #8FAF88 100%); box-shadow: 0 4px 12px rgba(125,155,118,0.35);"
        >
            Crear mi cuenta en Sukha
        </button>

        <p class="text-center text-sm" style="color: #6B7B6E;">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="font-semibold hover:underline" style="color: #7D9B76;">Inicia sesión</a>
        </p>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(
                    function(pos) {
                        var lat = document.getElementById('latitud');
                        var lon = document.getElementById('longitud');
                        if (lat && lon) {
                            lat.value = pos.coords.latitude.toFixed(7);
                            lon.value = pos.coords.longitude.toFixed(7);
                        }
                    },
                    function(err) {
                        // Opcional: no bloquea el registro si se niega
                        console.log('Geolocalización omitida:', err.message);
                    },
                    { timeout: 6000, maximumAge: 60000, enableHighAccuracy: false }
                );
            }
        });
    </script>
</x-guest-layout>
