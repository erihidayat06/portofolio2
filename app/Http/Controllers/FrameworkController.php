<?php

namespace App\Http\Controllers;

use App\Models\Framework;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FrameworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Framework::latest()->get();
        return view('admin.framework.index',  compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.framework.create');
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
            $gambarPath = $request->file('gambar')->store('Framework', 'public');
        }

        // Simpan ke database
        Framework::create([
            'nama' => $request->nama,
            'gambar' => $gambarPath, // string (bukan array lagi)
        ]);


        return redirect()->route('framework.index')->with('success', 'Framework berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Framework $framework)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Framework $framework)
    {
        return view('admin.framework.edit', compact('framework'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Framework $framework)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($framework->gambar && Storage::disk('public')->exists($framework->gambar)) {
                Storage::disk('public')->delete($framework->gambar);
            }

            // Upload gambar baru
            $gambarPath = $request->file('gambar')->store('framework', 'public');
            $framework->gambar = $gambarPath;
        }

        $framework->nama = $request->nama;
        $framework->save();

        return redirect()->route('framework.index')->with('success', 'framework berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Framework $framework)
    {
        // Hapus gambar dari storage jika ada
        if ($framework->gambar && Storage::disk('public')->exists($framework->gambar)) {
            Storage::disk('public')->delete($framework->gambar);
        }

        // Hapus data dari database
        $framework->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data bahasa berhasil dihapus.');
    }
}
