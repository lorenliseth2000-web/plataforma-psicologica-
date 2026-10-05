<section>
    <header>
        <h2 class="text-lg font-bold text-slate-900">
            {{ __('Información del Perfil y Preferencias de Bienestar') }}
        </h2>

        <p class="mt-1 text-xs text-slate-500">
            {{ __("Actualiza los datos de tu cuenta y tus preferencias de relajación.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <x-input-label for="name" :value="__('Nombre Completo')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full text-xs" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Correo Electrónico')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full text-xs" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label for="occupation" :value="__('Ocupación o Actividad Principal')" />
                <x-text-input id="occupation" name="occupation" type="text" class="mt-1 block w-full text-xs" :value="old('occupation', $user->occupation)" placeholder="Ej: Estudiante, Ingeniero, Docente..." />
                <x-input-error class="mt-2" :messages="$errors->get('occupation')" />
            </div>

            <div>
                <x-input-label for="birthdate" :value="__('Fecha de Nacimiento')" />
                <x-text-input id="birthdate" name="birthdate" type="date" class="mt-1 block w-full text-xs" :value="old('birthdate', $user->birthdate ? $user->birthdate->format('Y-m-d') : '')" />
                <x-input-error class="mt-2" :messages="$errors->get('birthdate')" />
            </div>
        </div>

        <!-- Preferencias de Bienestar -->
        <div class="pt-4 border-t border-slate-100 space-y-4">
            <h3 class="text-sm font-bold text-slate-800">Preferencias de la Plataforma</h3>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="ambient_sound" :value="__('Sonido Ambiental Preferido')" />
                    <select id="ambient_sound" name="preferences[ambient_sound]" class="mt-1 block w-full rounded-md border-slate-300 shadow-xs focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                        <option value="ocean_waves" {{ ($user->preferences['ambient_sound'] ?? '') === 'ocean_waves' ? 'selected' : '' }}>Olas del mar suaves</option>
                        <option value="rain" {{ ($user->preferences['ambient_sound'] ?? '') === 'rain' ? 'selected' : '' }}>Lluvia tranquila</option>
                        <option value="forest" {{ ($user->preferences['ambient_sound'] ?? '') === 'forest' ? 'selected' : '' }}>Bosque y viento sereno</option>
                        <option value="binaural" {{ ($user->preferences['ambient_sound'] ?? '') === 'binaural' ? 'selected' : '' }}>Ondas binaurales Theta (Relajación)</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 pt-6">
                    <input type="checkbox" id="daily_reminder" name="preferences[daily_reminder]" value="1" {{ !empty($user->preferences['daily_reminder']) ? 'checked' : '' }} class="rounded-sm border-slate-300 text-indigo-600 shadow-xs focus:ring-indigo-500">
                    <label for="daily_reminder" class="text-xs text-slate-700 font-semibold">
                        Activar recordatorio diario para el check-in emocional
                    </label>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar Cambios') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-bold text-emerald-600"
                >{{ __('Guardado exitosamente.') }}</p>
            @endif
        </div>
    </form>
</section>
