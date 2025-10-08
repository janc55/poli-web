<section id="promociones" class="py-20 bg-white">
    <div class="container mx-auto px-4">
        {{-- Título --}}
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Promociones Especiales</h2>
            <p class="text-xl text-gray-600">Aprovecha nuestras ofertas especiales en servicios médicos</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @forelse ($promotions as $promo)
                <div class="relative bg-white rounded-xl border border-gray-200 p-6 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    {{-- Badge de descuento --}}
                    <div class="absolute top-4 right-4">
                        <span class="inline-block bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-full transition">
                            -{{ number_format($promo->discount_percentage, 0)}}%
                        </span>
                    </div>

                    {{-- Contenido de la tarjeta --}}
                    <div class="pb-4">
                        <h3 class="text-xl text-gray-800 font-bold pr-16 mb-2">{{ $promo->title }}</h3>
                        <p class="text-gray-600 text-sm">{{ $promo->short_description, 0}}</p>
                    </div>

                    <div>
                        <p class="text-sm text-green-600 font-medium mb-4">{{ $promo->validity_notes }}</p>
                        <a href="#contacto" class="inline-block w-full text-center bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            Solicitar Información
                        </a>
                    </div>
                </div>
            @empty
                {{-- Fallback si no hay promociones activas --}}
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500">No hay promociones activas en este momento.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
