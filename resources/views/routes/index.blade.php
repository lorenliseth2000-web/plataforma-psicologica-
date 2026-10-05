@extends('layouts.app')

@section('title', 'Rutas de AtenciÃ³n y LÃ­neas de Emergencia')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Banner de Emergencia Destacado -->
        <div class="rounded-3xl p-6 sm:p-10 text-white shadow-xl space-y-6" style="background: linear-gradient(135deg, #C0392B 0%, #2C3E35 100%);">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-3 max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider" style="background: rgba(255,255,255,0.2);">
                        <span class="w-2.5 h-2.5 rounded-full bg-white animate-ping"></span>
                        AtenciÃ³n Inmediata 24 Horas / 7 DÃ­as
                    </div>
                    <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight">
                        LÃ­neas de Escucha y OrientaciÃ³n PsicolÃ³gica
                    </h1>
                    <p class="text-xs sm:text-sm leading-relaxed font-normal" style="color: rgba(255,255,255,0.85);">
                        Si tÃº o alguien cercano experimenta un momento de angustia intensa, desbordamiento emocional o crisis, comunÃ­cate de forma inmediata, gratuita y confidencial con los equipos profesionales de salud mental.
                    </p>
                </div>

                <div class="flex flex-wrap md:flex-col gap-3 shrink-0">
                    <a href="tel:{{ $primaryPhone }}" class="px-6 py-4 rounded-2xl bg-white font-extrabold text-sm hover:bg-rose-50 transition shadow-lg flex items-center justify-center gap-2" style="color: #9B1C1C;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        LÃ­nea {{ $primaryPhone }} (Gratuita)
                    </a>
                    <a href="tel:{{ $nationalPhone }}" class="px-6 py-3 rounded-2xl font-bold text-xs text-white hover:opacity-90 transition text-center flex items-center justify-center gap-2" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
                        LÃ­nea Nacional {{ $nationalPhone }} (OpciÃ³n 4)
                    </a>
                </div>
            </div>
        </div>

        {{-- ===== GEOLOCALIZACIÃ“N ===== --}}
        <div class="rounded-3xl p-6" style="background: white; border: 1px solid #D4E5D2; box-shadow: 0 4px 20px rgba(125,155,118,0.08);"
             x-data="geoLocator()" x-init="init()">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-lg font-bold" style="color: #2C3E35; font-family: 'Playfair Display', Georgia, serif;">
                        Servicios cerca de tu ubicaciÃ³n
                    </h2>
                    <p class="text-xs mt-0.5" style="color: #6B7B6E;">
                        Tu ubicaciÃ³n solo se usa para calcular distancias. No se almacena en nuestros servidores.
                    </p>
                </div>

                <button @click="requestLocation()"
                        :disabled="loading"
                        class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition whitespace-nowrap"
                        style="background: #EAF2E9; color: #2C5E28; border: 1.5px solid #B5C9B3;"
                        onmouseover="if(!this.disabled) this.style.background='#D4E5D2'"
                        onmouseout="this.style.background='#EAF2E9'">
                    <svg class="w-4 h-4" :class="loading ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span x-text="loading ? 'Buscando...' : (located ? 'Actualizar ubicaciÃ³n' : 'Encontrar servicios cercanos')"></span>
                </button>
            </div>

            {{-- Error --}}
            <div x-show="error" class="rounded-xl px-4 py-3 text-xs mb-4" style="background: #FFF5F0; border: 1px solid #FBBAA0; color: #7A3A1E;" x-text="error"></div>

            {{-- Resultados ordenados --}}
            <div x-show="located && sortedRoutes.length" class="space-y-3">
                <p class="text-xs font-semibold uppercase tracking-wide mb-3" style="color: #4A6A55;">
                    Ordenados por distancia desde tu ubicaciÃ³n:
                </p>
                <template x-for="route in sortedRoutes.slice(0, 5)" :key="route.id">
                    <div class="flex items-center justify-between gap-4 rounded-2xl p-4" style="background: #F5FAF4; border: 1px solid #D4E5D2;">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold truncate" style="color: #2C3E35;" x-text="route.name"></p>
                            <p class="text-xs mt-0.5 truncate" style="color: #6B7B6E;" x-text="route.institution"></p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="px-2 py-1 rounded-lg text-xs font-black" style="background: #EAF2E9; color: #2C5E28;" x-text="route.distance ? route.distance + ' km' : 'Sin coordenadas'"></span>
                            <template x-if="route.maps_url">
                                <a :href="route.maps_url" target="_blank"
                                   class="px-3 py-1.5 rounded-xl text-xs font-bold transition"
                                   style="background: #7D9B76; color: white;">
                                    Indicaciones
                                </a>
                            </template>
                            <template x-if="route.phone">
                                <a :href="'tel:' + route.phone"
                                   class="px-3 py-1.5 rounded-xl text-xs font-bold transition"
                                   style="background: white; border: 1px solid #D4E5D2; color: #4A6A55;">
                                    Llamar
                                </a>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="located && !sortedRoutes.length" class="text-xs py-4 text-center" style="color: #8FAF88;">
                No hay servicios con coordenadas registradas aÃºn. El directorio completo estÃ¡ abajo.
            </div>
        </div>

        <!-- Filtros y Directorio Institucional -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900">Directorio Institucional de Apoyo PsicolÃ³gico</h2>
                    <p class="text-xs text-slate-500 mt-1">Servicios clasificados por entidad, cobertura y modalidad de atenciÃ³n.</p>
                </div>

                <!-- Buscador -->
                <form method="GET" action="{{ route('routes.index') }}" class="flex items-center gap-2">
                    <div class="relative w-full sm:w-64">
                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Buscar entidad, ciudad o telÃ©fono..."
                               class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-xs text-slate-800 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </form>
            </div>

            <!-- PestaÃ±as de Filtro -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 border-t border-slate-100 pt-4 text-xs font-bold">
                <a href="{{ route('routes.index') }}" class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ !$category ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Todas las Rutas
                </a>
                <a href="{{ route('routes.index', ['category' => 'emergencia']) }}" class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ $category === 'emergencia' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                    Emergencias 24/7
                </a>
                <a href="{{ route('routes.index', ['category' => 'universitaria']) }}" class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ $category === 'universitaria' ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                    Centros Universitarios CAP
                </a>
                <a href="{{ route('routes.index', ['category' => 'eps']) }}" class="px-4 py-2 rounded-xl transition whitespace-nowrap {{ $category === 'eps' ? 'bg-teal-600 text-white' : 'bg-teal-50 text-teal-700 hover:bg-teal-100' }}">
                    Red EPS / Salud General
                </a>
            </div>
        </div>

        <!-- Grid de Rutas -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($routes as $route)
                <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $route->is_emergency ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst($route->category) }}
                            </span>
                            <span class="text-xs font-semibold text-slate-500">
                                {{ $route->available_hours }}
                            </span>
                        </div>

                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">
                                {{ $route->name }}
                            </h3>
                            <div class="text-xs font-semibold text-indigo-600 mt-0.5">
                                {{ $route->institution }}
                            </div>
                        </div>

                        <p class="text-xs text-slate-600 leading-relaxed">
                            {{ $route->description }}
                        </p>
                    </div>

                    <!-- Datos de Contacto y Botones -->
                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        @if($route->phone)
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-400 font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    TelÃ©fono:
                                </span>
                                <a href="tel:{{ $route->phone }}" class="font-extrabold text-indigo-600 hover:text-indigo-800">
                                    {{ $route->phone }}
                                </a>
                            </div>
                        @endif

                        @if($route->whatsapp)
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-400 font-medium">WhatsApp:</span>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $route->whatsapp) }}" target="_blank" class="font-bold text-emerald-600 hover:text-emerald-800">
                                    {{ $route->whatsapp }}
                                </a>
                            </div>
                        @endif

                        @if($route->maps_url)
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-400 font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    DirecciÃ³n:
                                </span>
                                <a href="{{ $route->maps_url }}" target="_blank" class="font-bold text-emerald-700 hover:underline text-right max-w-[60%]">
                                    {{ $route->address ?? 'Ver en mapa' }}
                                </a>
                            </div>
                        @endif

                        <div class="pt-1 flex gap-2">
                            @if($route->phone)
                                <a href="tel:{{ $route->phone }}" class="flex-1 py-2.5 rounded-xl bg-slate-900 hover:bg-rose-600 text-white font-bold text-xs text-center transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <span>Llamar</span>
                                </a>
                            @endif
                            @if($route->maps_url)
                                <a href="{{ $route->maps_url }}" target="_blank"
                                   class="py-2.5 px-3 rounded-xl font-bold text-xs transition flex items-center gap-1"
                                   style="background: #EAF2E9; color: #2C5E28; border: 1px solid #B5C9B3;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    Mapa
                                </a>
                            @elseif($route->website)
                                <a href="{{ $route->website }}" target="_blank" class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs text-center transition flex items-center justify-center gap-1.5">
                                    <span>Portal Web</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-3xl border border-slate-200 p-6">
                    <p class="text-sm font-semibold text-slate-700">No se encontraron rutas con los tÃ©rminos indicados.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>

@php
    $routesCoordsData = $routes->filter(fn($r) => $r->latitude && $r->longitude)->map(fn($r) => [
        'id' => $r->id,
        'name' => $r->name,
        'institution' => $r->institution,
        'phone' => $r->phone,
        'latitude' => $r->latitude,
        'longitude' => $r->longitude,
        'maps_url' => $r->maps_url,
    ])->values();
@endphp
@push('scripts')
<script>
// Datos de rutas con coordenadas (pasados desde PHP)
const routesWithCoords = {!! json_encode($routesCoordsData) !!};

function geoLocator() {
    return {
        loading: false,
        located: false,
        error: null,
        sortedRoutes: [],
        userLat: null,
        userLng: null,

        init() {},

        requestLocation() {
            if (!navigator.geolocation) {
                this.error = 'Tu navegador no soporta geolocalizaciÃ³n.';
                return;
            }
            this.loading = true;
            this.error = null;

            navigator.geolocation.getCurrentPosition(
                (pos) => {
                    this.userLat = pos.coords.latitude;
                    this.userLng = pos.coords.longitude;
                    this.calculateDistances();
                    this.loading = false;
                    this.located = true;
                },
                (err) => {
                    this.loading = false;
                    this.error = err.code === 1
                        ? 'Permiso de ubicaciÃ³n denegado. ActÃ­valo en la configuraciÃ³n del navegador para usar esta funciÃ³n.'
                        : 'No pudimos obtener tu ubicaciÃ³n. Intenta de nuevo.';
                },
                { timeout: 10000, maximumAge: 60000 }
            );
        },

        calculateDistances() {
            const routes = routesWithCoords.map(r => ({
                ...r,
                distance: r.latitude && r.longitude
                    ? this.haversineKm(this.userLat, this.userLng, r.latitude, r.longitude)
                    : null,
            }));
            this.sortedRoutes = routes
                .filter(r => r.distance !== null)
                .sort((a, b) => a.distance - b.distance)
                .map(r => ({ ...r, distance: r.distance.toFixed(1) }));
        },

        haversineKm(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) ** 2
                + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) ** 2;
            return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        },
    };
}
</script>
@endpush
@endsection
