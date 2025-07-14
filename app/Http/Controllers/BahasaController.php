<?php

namespace App\Http\Controllers;

use App\Models\Bahasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BahasaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Bahasa::latest()->get();
        return view('admin.bahasa.index',  compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.bahasa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // hanya 1 gambar
        ]);

        // Inisialisasi path gambar (nullable)
        $gambarPath = null;

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('bahasa', 'public');
        }

        // Simpan ke database
        Bahasa::create([
            'nama' => $request->nama,
            'gambar' => $gambarPath, // string (bukan array lagi)
        ]);


        return redirect()->route('bahasa.index')->with('success', 'Bahasa berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bahasa $bahasa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bahasa $bahasa)
    {
        return view('admin.bahasa.edit', compact('bahasa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bahasa $bahasa)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($bahasa->gambar && Storage::disk('public')->exists($bahasa->gambar)) {
                Storage::disk('public')->delete($bahasa->gambar);
            }

            // Upload gambar baru
            $gambarPath = $request->file('gambar')->store('bahasa', 'public');
            $bahasa->gambar = $gambarPath;
        }

        $bahasa->nama = $request->nama;
        $bahasa->save();

        return redirect()->route('bahasa.index')->with('success', 'Bahasa berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bahasa $bahasa)
    {
        // Hapus gambar dari storage jika ada
        if ($bahasa->gambar && Storage::disk('public')->exists($bahasa->gambar)) {
            Storage::disk('public')->delete($bahasa->gambar);
        }

        // Hapus data dari database
        $bahasa->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data bahasa berhasil dihapus.');
    }
}
