<section id="servicios" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        {{-- Título --}}
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Nuestros Servicios</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Ofrecemos atención médica integral con profesionales especializados y tecnología de última generación
            </p>
        </div>
        
        {{-- Servicios generales en grid --}}
        @php
            $services = [
                [
                    'icon' => 'pediatrics',
                    'title' => 'PEDIATRÍA',
                    'description' => 'Cuidado médico especializado para niños y adolescentes',
                    'features' => ['Consultas especializadas', 'Seguimiento del crecimiento', 'Control de esquemas vacunatorios', 'Control de niño sano'],
                ],
                [
                    'icon' => 'uterus',
                    'title' => 'GINECOLOGÍA OBSTÉTRICA',
                    'description' => 'Salud femenina y acompañamiento durante el embarazo',
                    'features' => ['Control prenatal', 'Planificación familiar', 'Citología', 'Salud Reproductiva'],
                ],
                [
                    'icon' => 'shield-virus',
                    'title' => 'MEDICINA INTERNA',
                    'description' => 'Diagnóstico y tratamiento integral de enfermedades en adultos',
                    'features' => ['Diagnóstico integral', 'Tratamiento especializado', 'Seguimiento médico', 'Medicina familiar'],
                ],
                [
                    'icon' => 'stethoscope',
                    'title' => 'MEDICINA PREVENTIVA',
                    'description' => 'Atención médica básica y orientación para toda la familia',
                    'features' => ['Consulta general', 'Medicina familiar', 'Orientación médica', 'Atención primaria'],
                ],
            ];
        @endphp

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            @foreach ($services as $service)
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-2">
                    <div class="mx-auto mb-4 p-3 bg-red-50 rounded-full w-16 h-16 text-red-600">
                        <x-dynamic-component :component="'healthicons.' . $service['icon']" />
                    </div>
                    <h3 class="text-red-600 text-lg font-bold mb-2">{{ $service['title'] }}</h3>
                    <p class="text-sm text-gray-600 mb-4">{{ $service['description'] }}</p>
                    <ul class="space-y-1 text-left text-sm text-gray-700">
                        @foreach ($service['features'] as $feature)
                            <li class="flex items-center">
                                <x-lucide-check-circle class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" />
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        {{-- Servicios especializados (Laboratorio y Ecografía) --}}
        <div class="grid lg:grid-cols-2 gap-8">
            @php
                $labServices = [
                    'Hematología', 'Química sanguínea', 'Uroanálisis', 'Diagnóstico de embarazo',
                    'Coprología', 'Inmunología', 'Pruebas COVID', 'Función endocrina', 'Pruebas de Paternidad',
                ];

                $ultrasoundServices = [
                    'Abdomen', 'Renal', 'Obstétrica', 'Vesico prostático',
                    'Partes blandas', 'Transvaginal', 'Doppler',
                ];

                // Servicios de imagenología adicionales
                $tomographyServices = [
                    'Abdomen', 'Pelvis', 'Cerebro', 'Cara y Base de Cráneo',
                    'Columna', 'Cuello', 'Tórax', 'Estudios con y sin contraste',
                ];
                $xrayServices = [
                    'Abdomen', 'Radiología Ósea', 'Cráneo y Cara',
                    'Columna', 'Extremidades Superiores', 'Extremidades Inferiores', 'Tórax',
                ];

            @endphp

            {{-- Laboratorio Clínico --}}
            <div class="bg-white rounded-xl border border-gray-200 transition-all duration-300 hover:shadow-md hover:-translate-y-2">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-red-600 flex items-center text-lg font-bold">
                        <div class="w-8 h-8 mr-2">
                            <x-healthicons.lab />
                        </div>
                        LABORATORIO CLÍNICO
                    </h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-800">
                        @foreach ($labServices as $item)
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-red-600 rounded-full mr-2"></div>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Ecografía --}}
            <div class="bg-white rounded-xl border border-gray-200 transition-all duration-300 hover:shadow-md hover:-translate-y-2">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-red-600 flex items-center text-lg font-bold">
                        <div class="w-8 h-8 mr-2">
                            <x-healthicons.ultrasound />
                        </div>
                        ECOGRAFÍA
                    </h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-800">
                        @foreach ($ultrasoundServices as $item)
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-red-600 rounded-full mr-2"></div>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Tomografia --}}
            <div class="bg-white rounded-xl border border-gray-200 transition-all duration-300 hover:shadow-md hover:-translate-y-2">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-red-600 flex items-center text-lg font-bold">
                        <div class="w-8 h-8 mr-2">
                            <x-healthicons.tomo />
                        </div>
                        TOMOGRAFÍA
                    </h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-800">
                        @foreach ($tomographyServices as $item)
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-red-600 rounded-full mr-2"></div>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- Rayos X --}}
            <div class="bg-white rounded-xl border border-gray-200 transition-all duration-300 hover:shadow-md hover:-translate-y-2">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-red-600 flex items-center text-lg font-bold">
                        <div class="w-8 h-8 mr-2">
                            <x-healthicons.ray-x />
                        </div>
                        RAYOS X
                    </h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-800">
                        @foreach ($xrayServices as $item)
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-red-600 rounded-full mr-2"></div>
                                {{ $item }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

