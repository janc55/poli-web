<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function index()
    {
        $services = Service::with('doctors')->orderBy('name')->get(); // Carga especialidades con doctores relacionados

        return view('pages.citas', compact('services'));
    }
}
