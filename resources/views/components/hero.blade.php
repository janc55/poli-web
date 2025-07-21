{{-- resources/views/components/hero.blade.php --}}
<section class="bg-gradient-to-br from-red-50 to-white py-20">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            {{-- Columna Izquierda (Texto) --}}
            <div
                x-cloak
                x-data="{
                    visible: false,
                    init() {
                        setTimeout(() => {
                            this.visible = true;
                        }, 150); // Retraso mayor para mejor sincronización
                    }
                }"
                x-intersect.once="visible = true"
                :class="{
                    'translate-y-0 opacity-100': visible,
                    'translate-y-8 opacity-0': !visible
                }"
                class="transform transition-[transform,opacity] duration-1000 ease-[cubic-bezier(0.25,0.1,0.25,1)]"
            >
                <div class="flex items-center mb-6">
                    <img 
                        src="{{ asset('images/logo-symbol.png') }}" 
                        alt="Policonsultorio UNIOR" 
                        class="h-16 w-16 mr-4"
                        loading="lazy"
                    />
                    <span class="inline-block bg-red-100 text-red-800 text-sm font-semibold px-3 py-1 rounded-full">
                        Innovación en Salud
                    </span>
                </div>
                <h1 class="text-4xl lg:text-6xl font-bold text-gray-800 mb-6">
                    INNOVACIÓN EN
                    <span class="text-red-600 block">SALUD, EDUCACIÓN</span>
                    <span class="text-red-600">Y SERVICIO</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    Atención médica integral con tecnología de vanguardia y el mejor equipo profesional para cuidar tu salud
                    y la de tu familia.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#agendar" class="inline-flex items-center justify-center px-6 py-3 bg-red-600 hover:bg-red-700 text-white text-lg font-medium rounded-lg transition-all duration-300 hover:shadow-lg">
                        <x-lucide-calendar class="w-5 h-5 mr-2" />
                        Agendar Cita
                    </a>
                    <a href="#servicios" class="inline-flex items-center justify-center px-6 py-3 border border-red-600 text-red-600 hover:bg-red-50 text-lg font-medium rounded-lg transition-all duration-300">
                        Ver Servicios
                        <x-lucide-arrow-right class="w-5 h-5 ml-2" />
                    </a>
                </div>
            </div>

            {{-- Columna Derecha (Tarjeta con imagen) --}}
            <div
                x-cloak
                x-data="{
                    visible: false,
                    init() {
                        setTimeout(() => {
                            this.visible = true;
                        }, 300); // Retraso mayor para animación escalonada
                    }
                }"
                x-intersect.once="visible = true"
                :class="{
                    'translate-y-0 opacity-100': visible,
                    'translate-y-8 opacity-0': !visible
                }"
                class="transform transition-[transform,opacity] duration-1000 ease-[cubic-bezier(0.25,0.1,0.25,1)]"
            >
                <div class="relative group rounded-3xl overflow-hidden border border-red-100 shadow-md h-full min-h-[400px]">
                    {{-- Imagen como capa de fondo --}}
                    <img 
                        src="{{ asset('images/tomografo.jpeg') }}" 
                        alt="Tomógrafo" 
                        class="absolute inset-0 w-full h-full object-cover"
                        loading="lazy"
                    />

                    {{-- Capa oscura translúcida para mejor contraste --}}
                    <div class="absolute inset-0 bg-black/20 z-0"></div>

                    {{-- Contenido visible sobre la imagen --}}
                    <div class="relative z-10 p-8 h-full flex flex-col justify-center">
                        {{-- Logo con fondo semitransparente --}}
                        <div class="text-center mb-8">
                            <div class="inline-block p-4 rounded-xl backdrop-blur-sm">
                                <img 
                                    src="{{ asset('images/logo-vertical.png') }}" 
                                    alt="Policonsultorio UNIOR" 
                                    class="h-32 w-auto mx-auto"
                                    loading="lazy"
                                />
                            </div>
                        </div>

                        {{-- Tarjeta de servicios --}}
                        <div class="bg-red-600/95 rounded-2xl p-6 text-white backdrop-blur-sm border border-red-700/20 transition-all duration-500 group-hover:shadow-xl group-hover:scale-[1.02]">
                            <div class="flex items-center mb-4">
                                <x-lucide-activity class="w-6 h-6 mr-3" />
                                <h3 class="text-xl font-bold">Servicios Disponibles</h3>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="flex items-center">
                                    <x-lucide-check-circle class="w-4 h-4 mr-2" />
                                    <span class="text-sm">Rayos X</span>
                                </div>
                                <div class="flex items-center">
                                    <x-lucide-check-circle class="w-4 h-4 mr-2" />
                                    <span class="text-sm">Tomografía</span>
                                </div>
                                <div class="flex items-center">
                                    <x-lucide-check-circle class="w-4 h-4 mr-2" />
                                    <span class="text-sm">Ecografía</span>
                                </div>
                                <div class="flex items-center">
                                    <x-lucide-check-circle class="w-4 h-4 mr-2" />
                                    <span class="text-sm">Laboratorio</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>