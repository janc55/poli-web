<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Promotion;
use Illuminate\Http\Request;

class NoticiaController extends Controller
{
    public function index()
    {
        // Para la lista, usa Livewire para reactividad
        return view('noticias.index');
    }

    public function show($slug)
    {
        $newsItem = News::where('slug', $slug)->published()->firstOrFail();
        $promotions = Promotion::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('noticias.show', compact('newsItem', 'promotions'));
    }
}
