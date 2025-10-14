{{-- resources/views/components/news.blade.php --}}
@props(['news' => collect()])

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <!-- Título de la sección -->
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Últimas Noticias</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Mantente al día con las novedades de Policonsultorio UNIOR.</p>
        </div>

        <!-- Grid de noticias -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($news as $newsItem)
                <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Imagen -->
                    <div class="h-48 w-full relative overflow-hidden">
                        <img src="{{ $newsItem['image'] ? asset('storage/' . $newsItem['image']) : asset('images/placeholder-news.jpg') }}" 
                             alt="{{ $newsItem['title'] }}" 
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>

                    <!-- Contenido -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-red-600 font-medium">{{ $newsItem['formatted_date'] }}</span>
                            <span class="text-sm text-gray-500">• {{ Str::limit($newsItem['category'], 15) }}</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3 line-clamp-2">{{ $newsItem['title'] }}</h3>
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $newsItem['excerpt'] }}</p>
                        <a href="{{ route('noticias.show', $newsItem['slug']) }}" 
                           class="inline-flex items-center text-red-600 hover:text-red-800 font-medium text-sm">
                            Leer más
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500">No hay noticias disponibles por el momento.</p>
                </div>
            @endforelse
        </div>

        <!-- Botón para ver todas -->
        <div class="text-center mt-12">
            <a href="{{ route('noticias.index') }}" 
               class="inline-block bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg font-medium transition-colors">
                Ver todas las noticias
            </a>
        </div>
    </div>
</section>