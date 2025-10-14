{{-- resources/views/noticias/show.blade.php --}}
<x-layouts.app>
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="lg:grid lg:grid-cols-12 lg:gap-8 mb-12">
                <!-- Columna de noticias (full en móvil/tablet, 9/12 en desktop) -->
                <div class="lg:col-span-9">
                    <article>
                        @if($newsItem->image)
                            <img src="{{ asset('storage/' . $newsItem->image) }}" 
                                alt="{{ $newsItem->title }}" 
                                class="w-full h-96 object-cover rounded-lg mb-8">
                        @endif
                        
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <span class="text-sm text-red-600 font-medium">{{ $newsItem->date?->format('d M Y') }}</span>
                                @if($newsItem->category)
                                    <span class="text-sm text-gray-500 ml-4">• {{ $newsItem->category }}</span>
                                @endif
                            </div>
                            <a href="{{ route('noticias.index') }}" 
                            class="text-sm text-red-600 hover:text-red-800 font-medium">
                                ← Volver a noticias
                            </a>
                        </div>
                        
                        <h1 class="text-4xl font-bold text-gray-900 mb-6">{{ $newsItem->title }}</h1>
                        
                        <div class="prose prose-lg max-w-none">
                            {!! $newsItem->content !!}
                        </div>
                    </article>
                </div>
                <!-- Sidebar de promociones (oculto en móvil, 3/12 en desktop, paralelo a todo el contenedor de noticias) -->
                <aside class="hidden lg:block lg:col-span-3">
                    <x-promotions-sidebar :promotions="$promotions" />
                </aside>
            </div>
        </div>
    </section>
</x-layouts.app>