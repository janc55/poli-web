<?php

namespace App\Livewire;

use App\Models\News;
use App\Models\Promotion;
use Livewire\Component;
use Livewire\WithPagination;

class NoticiasList extends Component
{
    use WithPagination;

    public function render()
    {
        $news = News::published() // Asumiendo que agregas este scope al modelo News
            ->latest('date')
            ->paginate(9); // 9 por página, grid 3x3

        $promotions = Promotion::where("is_active", true) // O tu lógica para promociones
            ->latest()
            ->take(3) // Ej: 3 banners en sidebar
            ->get();

        return view('livewire.noticias-list', compact('news', 'promotions'));
    }
}
 