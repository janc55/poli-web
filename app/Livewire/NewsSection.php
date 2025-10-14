<?php

namespace App\Livewire;

use App\Models\News;
use Livewire\Component;
use Illuminate\Support\Str;

class NewsSection extends Component
{
    public function render()
    {
        $news = News::published() // Usa el scope si lo tienes
            ->latest('date')
            ->take(3)
            ->get()
            ->map(function ($newsItem) {
                return [
                    'id' => $newsItem->id,
                    'title' => $newsItem->title,
                    'excerpt' => Str::limit(strip_tags($newsItem->content), 150),
                    'image' => $newsItem->image,
                    'formatted_date' => $newsItem->date?->format('d M Y'),
                    'category' => $newsItem->category ?? 'General',
                    'slug' => $newsItem->slug ?? Str::slug($newsItem->title),
                ];
            });

        // Debug temporal: descomenta para ver si $news se genera
        //dd($news); // Si ves el array aquí, el problema está en la vista Livewire

        return view('livewire.news-section', compact('news')); // Esto pasa $news a la vista Livewire
    }
}
