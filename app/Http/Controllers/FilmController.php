<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilmController extends Controller
{
    public function index()
    {
        return view('film.index');
    }

    public function show(Request $request)
    {
        // Mengambil query parameter dari URL, contoh: /film/show?type=movie&id=12345
        $id = $request->query('id');
        $type = $request->query('type', 'movie'); // Default ke 'movie' jika kosong

        return view('film.show', compact('id', 'type'));
    }
}
