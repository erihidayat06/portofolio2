<?php

namespace App\Http\Controllers\Home;

use App\Models\Bahasa;
use App\Models\Framework;
use App\Models\Portofolio;
use App\Models\Protofolio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProfilWeb;

class HomeController extends Controller
{
    public function index()
    {

        $portofolios = Portofolio::orderBy('urutan', 'asc')->get();
        $profilWeb = ProfilWeb::get()->first();
        return view('home.index', [
            'portofolios' => $portofolios,
            'profilWeb' => $profilWeb,
        ]);
    }
}
