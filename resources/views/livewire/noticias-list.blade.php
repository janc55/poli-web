{{-- resources/views/livewire/noticias-list.blade.php --}}
<div>
    <div class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="lg:grid lg:grid-cols-12 lg:gap-8 mb-12">
                <!-- Columna de noticias (full en móvil/tablet, 9/12 en desktop) -->
                <div class="lg:col-span-9">
                    <!-- Título dentro de la columna de noticias -->
                    <h1 class="text-4xl font-bold text-center mb-12">Todas las Noticias</h1>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @forelse($news as $newsItem)
                            <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                                <a href="{{ route('noticias.show', $newsItem->slug) }}" class="block">
                                    <!-- Imagen -->
                                    <div class="h-48 w-full relative overflow-hidden">
                                        <img src="{{ $newsItem->image ? asset('storage/' . $newsItem->image) : asset('images/placeholder-news.jpg') }}" 
                                             alt="{{ $newsItem->title }}" 
                                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    </div>

                                    <!-- Contenido -->
                                    <div class="p-6">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm text-red-600 font-medium">{{ $newsItem->date?->format('d M Y') }}</span>
                                            <span class="text-sm text-gray-500">• {{ Str::limit($newsItem->category ?? 'General', 15) }}</span>
                                        </div>
                                        <h3 class="text-xl font-semibold text-gray-900 mb-3 line-clamp-2">{{ $newsItem->title }}</h3>
                                        <p class="text-gray-600 mb-4 line-clamp-3">
                                            {{ $newsItem->excerpt ?? Str::limit(strip_tags($newsItem->content), 150) }}
                                        </p>
                                    </div>
                                </a>
                            </article>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-gray-500 text-lg">No hay noticias disponibles por el momento.</p>
                                <a href="{{ route('home') }}" class="mt-4 inline-block text-red-600 hover:text-red-800">Volver al inicio</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Sidebar de promociones (oculto en móvil, 3/12 en desktop, paralelo a todo el contenedor de noticias) -->
                <aside class="hidden lg:block lg:col-span-3">
                    <x-promotions-sidebar :promotions="$promotions" />
                </aside>
            </div>

            @if($news->hasPages())
                <div class="flex justify-center">
                    {{ $news->links() }} {{-- Paginación solo si hay páginas --}}
                </div>
            @endif
        </div>
    </div>
</div>