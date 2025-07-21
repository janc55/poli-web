<section id="promociones" class="py-20 bg-white">
    <div class="container mx-auto px-4">
        {{-- Título --}}
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Promociones Especiales</h2>
            <p class="text-xl text-gray-600">Aprovecha nuestras ofertas especiales en servicios médicos</p>
        </div>

        {{-- Promociones --}}
        @php
            $promotions = [
                [
                    'title' => 'Consulta Ginecológica Completa',
                    'discount' => '25%',
                    'description' => 'Incluye consulta + ecografía',
                    'validUntil' => 'Válido hasta fin de mes',
                ],
                [
                    'title' => 'Chequeo Médico Integral',
                    'discount' => '30%',
                    'description' => 'Consulta + laboratorio básico',
                    'validUntil' => 'Promoción especial',
                ],
                [
                    'title' => 'Paquete Familiar',
                    'discount' => '20%',
                    'description' => 'Consultas para toda la familia',
                    'validUntil' => 'Descuento por familia',
                ],
            ];
        @endphp

        <div class="grid md:grid-cols-3 gap-8">
            @foreach ($promotions as $promo)
                <div class="relative bg-white rounded-xl border border-gray-200 p-6 overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                    {{-- Badge de descuento --}}
                    <div class="absolute top-4 right-4">
                    <span class="inline-block bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-full transition">
                        -{{ $promo['discount'] }}
                    </span>
                    </div>

                    {{-- Contenido de la tarjeta --}}
                    <div class="pb-4">
                        <h3 class="text-xl text-gray-800 font-bold pr-16 mb-2">{{ $promo['title'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $promo['description'] }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-green-600 font-medium mb-4">{{ $promo['validUntil'] }}</p>
                        <a href="#contacto" class="inline-block w-full text-center bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            Solicitar Información
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
