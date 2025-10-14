{{-- resources/views/components/promotions-sidebar.blade.php --}}
@props(['promotions' => collect()])

@if($promotions->isNotEmpty())
    <div class="sticky top-8 space-y-6 bg-gray-200 p-4 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-center">Promociones</h2>
        @foreach($promotions as $promotion)
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 relative">
                {{-- Badge de descuento en la esquina superior derecha de la tarjeta completa --}}
                @if($promotion->discount_percentage)
                    <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg z-10">
                        -{{ number_format($promotion->discount_percentage, 0)}}%
                    </span>
                @endif
                
                @if($promotion->image_url)
                    <img src="{{ asset('storage/' . $promotion->image_url) }}" 
                         alt="{{ $promotion->title ?? 'Promoción' }}" 
                         class="w-full h-48 object-cover">
                @endif
                
                <div class="p-4 {{ $promotion->image ? '' : 'pt-8' }}"> {{-- Espacio extra si no hay imagen --}}
                    <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ $promotion->title ?? 'Descubre nuestra oferta' }}</h4>
                    <p class="text-sm text-gray-600 mb-4">{{ $promotion->description ?? '¡Aprovecha ahora!' }}</p>
                    @if($promotion->url)
                        <a href="{{ $promotion->url }}" 
                           class="inline-block bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium transition-colors">
                            {{ $promotion->cta ?? 'Ver más' }}
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-8 text-gray-500">
        <p>No hay promociones disponibles.</p>
    </div>
@endif