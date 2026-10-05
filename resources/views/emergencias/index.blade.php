@extends('layouts.app')
@section('title', 'Números de Emergencia')

@section('content')
<div class="py-8">
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8"
     x-data="emergencyLocator()"
     x-init="init()">

    {{-- Header --}}
    <div class="rounded-3xl p-6 sm:p-10" style="background: linear-gradient(135deg, #FDF0EC 0%, #FAF7F0 100%); border: 1px solid #E8C4B8;">
        <p class="text-[11px] font-bold uppercase tracking-widest mb-2" style="color: #C4856A;">Líneas de emergencia</p>
        <h1 class="font-playfair text-3xl sm:text-4xl font-bold" style="color: #2C3E35;">Ayuda disponible ahora</h1>
        <p class="mt-3 text-sm leading-relaxed max-w-xl" style="color: #6B7B6E;">
            Detectamos tu país automáticamente o puedes seleccionarlo manualmente. Todos los botones te permiten llamar directamente.
        </p>
    </div>

    {{-- Geo button --}}
    <div class="flex flex-wrap gap-3 items-center">
        <button @click="geoDetect()"
                :disabled="geoLoading"
                class="flex items-center gap-2 px-5 py-3 rounded-2xl text-sm font-semibold text-white transition-all"
                style="background: linear-gradient(135deg, #7D9B76, #8FAF88);">
            <svg x-show="!geoLoading" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3"/>
            </svg>
            <svg x-show="geoLoading" class="animate-spin" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4"/>
            </svg>
            <span x-text="geoLoading ? 'Detectando...' : 'Usar mi ubicación'"></span>
        </button>

        <span class="text-xs" style="color: #6B7B6E;">o selecciona tu país:</span>

        <select x-model="selectedCountry" @change="lookupCountry(selectedCountry)"
                class="px-4 py-2.5 rounded-xl text-sm border"
                style="border-color: #D4E5D2; color: #2C3E35; background: white;">
            <option value="">-- Seleccionar país --</option>
            @foreach($countries as $c)
                <option value="{{ $c['code'] }}">{{ $c['name'] }}</option>
            @endforeach
        </select>
    </div>

    {{-- Geo error --}}
    <div x-show="geoError" x-transition class="rounded-2xl px-5 py-4 text-sm" style="background: #FFF5F0; border: 1px solid #E8C4B8; color: #9B4D35;">
        <span x-text="geoError"></span>
    </div>

    {{-- Location detected label --}}
    <div x-show="locationLabel" x-transition>
        <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #7D9B76;">Tu ubicación detectada</p>
        <p class="text-lg font-playfair font-bold" style="color: #2C3E35;" x-text="locationLabel"></p>
        <p x-show="cityLabel" class="text-sm" style="color: #6B7B6E;" x-text="cityLabel"></p>
    </div>

    {{-- Numbers grid --}}
    <div x-show="numbers.length > 0" x-transition>
        <h2 class="font-playfair text-xl font-bold mb-4" style="color: #2C3E35;">Líneas de emergencia</h2>
        <div class="grid sm:grid-cols-2 gap-4">
            <template x-for="num in numbers" :key="num.key">
                <div class="rounded-2xl p-5 flex items-center justify-between gap-4"
                     style="background: white; border: 1px solid #D4E5D2; box-shadow: 0 2px 12px rgba(125,155,118,0.07);">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color: #7D9B76;" x-text="num.label"></p>
                        <p class="text-2xl font-playfair font-bold" style="color: #2C3E35;" x-text="num.phone"></p>
                    </div>
                    <a :href="'tel:' + num.phone"
                       class="flex-shrink-0 px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:opacity-90"
                       :style="num.key === 'salud_mental' ? 'background: #7D9B76;' : 'background: #C4856A;'">
                        Llamar
                    </a>
                </div>
            </template>
        </div>
        <div x-show="noteText" class="mt-4 text-xs leading-relaxed p-4 rounded-xl" style="background: #EAF2E9; color: #4A6A55;">
            <span x-text="noteText"></span>
        </div>
    </div>

    {{-- Empty state --}}
    <div x-show="!numbers.length && !geoLoading" x-transition class="text-center py-10">
        <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: #EAF2E9;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#7D9B76" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
            </svg>
        </div>
        <p class="text-sm" style="color: #6B7B6E;">Usa el botón de ubicación o selecciona tu país para ver los números correspondientes.</p>
    </div>

    {{-- Crisis box always visible --}}
    <div class="rounded-2xl p-5 text-center" style="background: #FDF0EC; border: 1px solid #E8C4B8;">
        <p class="text-xs font-bold uppercase tracking-widest mb-1" style="color: #9B4D35;">Si estás en peligro inmediato</p>
        <p class="text-sm" style="color: #7A2E1A;">Llama a los servicios de emergencia de tu país ahora mismo. No estás solo o sola.</p>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
function emergencyLocator() {
    return {
        geoLoading: false,
        geoError: '',
        locationLabel: '',
        cityLabel: '',
        numbers: [],
        noteText: '',
        selectedCountry: '',

        init() {},

        async geoDetect() {
            if (!navigator.geolocation) {
                this.geoError = 'Tu navegador no soporta geolocalización. Selecciona tu país manualmente.';
                return;
            }
            this.geoLoading = true;
            this.geoError = '';
            navigator.geolocation.getCurrentPosition(
                async (pos) => {
                    const { latitude, longitude } = pos.coords;
                    try {
                        const res = await fetch(
                            `https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=es`
                        );
                        const data = await res.json();
                        const code = data.countryCode || '';
                        this.locationLabel = data.countryName || code;
                        this.cityLabel = [data.city, data.principalSubdivision].filter(Boolean).join(', ');
                        this.selectedCountry = code;
                        await this.lookupCountry(code);
                    } catch {
                        this.geoError = 'No se pudo determinar tu país. Por favor selecciónalo manualmente.';
                    }
                    this.geoLoading = false;
                },
                (err) => {
                    this.geoLoading = false;
                    const msgs = {
                        1: 'Permiso de ubicación denegado. Selecciona tu país manualmente.',
                        2: 'No se pudo obtener la ubicación. Selecciona tu país manualmente.',
                        3: 'Tiempo de espera agotado. Selecciona tu país manualmente.',
                    };
                    this.geoError = msgs[err.code] || 'Error de ubicación. Selecciona tu país manualmente.';
                },
                { timeout: 10000, maximumAge: 300000 }
            );
        },

        async lookupCountry(code) {
            if (!code) return;
            try {
                const res = await fetch('{{ route('emergency.lookup') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '',
                    },
                    body: JSON.stringify({ country_code: code }),
                });
                const data = await res.json();
                this.numbers = data.numbers || [];
                this.noteText = data.note || '';
                if (!this.locationLabel && data.country_name) {
                    this.locationLabel = data.country_name;
                }
                if (!data.found) {
                    this.geoError = 'No encontramos un directorio específico para ese territorio. Mostrando número internacional.';
                }
            } catch {
                this.geoError = 'Error al cargar los números. Inténtalo nuevamente.';
            }
        },
    };
}
</script>
@endpush
