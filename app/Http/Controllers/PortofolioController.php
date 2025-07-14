<?php

namespace App\Http\Controllers;

use App\Models\Bahasa;
use App\Models\Framework;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortofolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Portofolio::get();
        $bahasas = Bahasa::latest()->get();
        $frameworks = Framework::latest()->get();
        return view('admin.projek.index', compact('data', 'bahasas', 'frameworks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bahasas = Bahasa::get();
        $frameworks = Framework::get();
        return view('admin.projek.create', compact('bahasas', 'frameworks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nm_projek' => 'required|string|max:255',
            'gambar.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'required|string',
            'bahasa_id' => 'required|array',
            'bahasa_id.*' => 'exists:bahasas,id',
            'framework_id' => 'required|array',
            'framework_id.*' => 'exists:frameworks,id',
            'link' => 'nullable|url',
        ]);

        // Simpan gambar ke storage
        $gambarPaths = [];
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $gambar) {
                $path = $gambar->store('projek_gambar', 'public');
                $gambarPaths[] = $path;
            }
        }

        // Simpan data projek
        $projek = Portofolio::create([
            'nm_projek' => $request->nm_projek,
            'gambar' => json_encode($gambarPaths), // Simpan sebagai array JSON
            'deskripsi' => $request->deskripsi,
            'bahasa_id' => json_encode($request->bahasa_id),
            'framework_id' => json_encode($request->framework_id),
            'link' => $request->link,
        ]);

        return redirect()->route('projek.index')->with('success', 'Portofolio berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Portofolio $portofolio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Portofolio $portofolio)
    {
        $bahasas = Bahasa::all();        // Ambil semua bahasa
        $frameworks = Framework::all();  // Ambil semua framework

        return view('admin.projek.edit', [
            'projek' => $portofolio,
            'bahasas' => $bahasas,
            'frameworks' => $frameworks,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Portofolio $portofolio)
    {
        $request->validate([
            'nm_projek' => 'required|string|max:255',
            'gambar.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'deskripsi' => 'nullable|string',
            'bahasa_id' => 'required|array|min:1',
            'framework_id' => 'required|array|min:1',
            'link' => 'nullable|url',
        ]);

        // Ambil gambar lama dari input (bentuk array string path)
        $gambarLama = $request->input('gambar_lama', []);

        // Ambil gambar sebelumnya yang disimpan di DB
        $gambarTersimpan = json_decode($portofolio->gambar ?? '[]', true);

        // Hapus gambar yang tidak dipertahankan
        foreach ($gambarTersimpan as $img) {
            if (!in_array($img, $gambarLama)) {
                Storage::disk('public')->delete($img);
            }
        }

        // Inisialisasi array final dari gambar yang dipertahankan
        $finalGambar = $gambarLama;

        // Simpan gambar baru jika ada
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $path = $file->store('projek', 'public');
                $finalGambar[] = $path;
            }
        }

        // Validasi total gambar maksimal 5
        if (count($finalGambar) > 5) {
            return back()->withErrors(['gambar' => 'Maksimal 5 gambar yang diperbolehkan.'])->withInput();
        }

        // Update ke database
        $portofolio->update([
            'nm_projek' => $request->nm_projek,
            'gambar' => json_encode($finalGambar),
            'deskripsi' => $request->deskripsi,
            'bahasa_id' => json_encode($request->bahasa_id),
            'framework_id' => json_encode($request->framework_id),
            'link' => $request->link,
        ]);

        return redirect()->route('projek.index')->with('success', 'Projek berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portofolio $portofolio)
    {
        // Hapus gambar dari storage jika ada
        $gambarArray = json_decode($portofolio->gambar, true);

        if ($gambarArray && is_array($gambarArray)) {
            foreach ($gambarArray as $gambar) {
                Storage::disk('public')->delete($gambar);
            }
        }

        // Hapus data dari database
        $portofolio->delete();

        return redirect()->route('projek.index')->with('success', 'Data projek berhasil dihapus!');
    }
}
