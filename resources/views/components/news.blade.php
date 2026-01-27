{{-- resources/views/components/news.blade.php --}}
@props(['news' => collect()])

<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-3">Últimas Noticias</h2>
            <p class="text-base text-gray-600 max-w-xl mx-auto">Mantente al día con las novedades de Policonsultorio UNIOR.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($news as $newsItem)
                <article class="flex flex-col bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300">
                    <div class="aspect-[4/3] w-full relative overflow-hidden bg-gray-200">
                        <img src="{{ $newsItem['image'] ? asset('storage/' . $newsItem['image']) : asset('images/placeholder-news.jpg') }}" 
                             alt="{{ $newsItem['title'] }}" 
                             class="w-full h-full object-contain md:object-cover hover:scale-105 transition-transform duration-500">
                    </div>

                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs font-bold px-2 py-1 bg-red-50 text-red-600 rounded">
                                {{ $newsItem['formatted_date'] }}
                            </span>
                            <span class="text-xs text-gray-400 font-medium italic">
                                #{{ Str::limit($newsItem['category'], 12) }}
                            </span>
                        </div>
                        
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 leading-tight">
                            {{ $newsItem['title'] }}
                        </h3>
                        
                        <p class="text-sm text-gray-600 mb-4 line-clamp-3 flex-grow">
                            {{ $newsItem['excerpt'] }}
                        </p>
                        
                        <div class="pt-4 border-t border-gray-50">
                            <a href="{{ route('noticias.show', $newsItem['slug']) }}" 
                               class="inline-flex items-center text-red-600 hover:text-red-700 font-semibold text-sm transition-colors group">
                                Leer noticia completa
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-16 bg-white rounded-lg border-2 border-dashed border-gray-200">
                    <p class="text-gray-400">No hay noticias disponibles por el momento.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('noticias.index') }}" 
               class="inline-block bg-red-600 hover:bg-red-700 text-white px-10 py-3 rounded-full font-bold shadow-md hover:shadow-lg transition-all active:scale-95">
                Ver todas las noticias
            </a>
        </div>
    </div>
</section>