<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Carga solo promociones activas, ordenadas por más recientes
        $promotions = Promotion::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.home', compact('promotions'));
    }
}
