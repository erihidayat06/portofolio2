<?php

namespace App\Http\Controllers;

use App\Models\Bahasa;
use App\Models\Framework;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class PortofolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Portofolio::orderBy('urutan', 'asc')->get();
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
            'bahasa_id' => 'nullable|array',
            'bahasa_id.*' => 'exists:bahasas,id',
            'framework_id' => 'nullable|array',
            'framework_id.*' => 'exists:frameworks,id',
            'link' => 'nullable|url',
        ]);

        // Simpan gambar ke storage
        $gambarPaths = [];
        // Di dalam fungsi store()
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $gambar) {
                // 1. Buat nama file dengan ekstensi .webp
                $filename = time() . '_' . uniqid() . '.webp';

                // 2. Baca gambar
                $img = Image::read($gambar);

                // 3. Resize (opsional) dan kompres ke WebP
                // Quality 70 adalah angka yang ideal (0-100)
                $img->scale(width: 800);
                $encoded = $img->toWebp(quality: 70);

                // 4. Simpan ke storage
                $path = 'projek_gambar/' . $filename;
                Storage::disk('public')->put($path, (string) $encoded);

                $gambarPaths[] = $path;
            }
        }

        // Ambil urutan tertinggi dan tambah 1
        $maxUrutan = Portofolio::max('urutan') ?? 0;

        // Simpan data projek
        $projek = Portofolio::create([
            'nm_projek' => $request->nm_projek,
            'gambar' => json_encode($gambarPaths), // Simpan sebagai array JSON
            'deskripsi' => $request->deskripsi,
            'bahasa_id' => json_encode($request->bahasa_id),
            'framework_id' => json_encode($request->framework_id),
            'link' => $request->link,
            'urutan' => $maxUrutan + 1,
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
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, Portofolio $portofolio)
    {
        $request->validate([
            'nm_projek' => 'required|string|max:255',
            'gambar.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        // 1. Ambil gambar lama dari database
        $gambarTersimpan = json_decode($portofolio->gambar ?? '[]', true);
        $gambarLama = $request->input('gambar_lama', []);

        // 2. Hapus gambar dari storage yang tidak ada di input "gambar_lama"
        foreach ($gambarTersimpan as $img) {
            if (!in_array($img, $gambarLama)) {
                Storage::disk('public')->delete($img);
            }
        }

        // 3. Mulai dengan gambar yang dipertahankan
        $finalGambar = $gambarLama;

        // 4. Proses gambar baru (jika ada) ke format WebP
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $filename = time() . '_' . uniqid() . '.webp';

                // Baca file dan konversi ke WebP
                $img = Image::read($file);
                $img->scale(width: 800);
                $encoded = $img->toWebp(quality: 70);

                $path = 'projek/' . $filename;
                Storage::disk('public')->put($path, (string) $encoded);

                $finalGambar[] = $path;
            }
        }

        // 5. Update ke database
        $portofolio->update([
            'nm_projek' => $request->nm_projek,
            'gambar' => json_encode($finalGambar),
            'deskripsi' => $request->deskripsi,
            'bahasa_id' => json_encode($request->bahasa_id),
            'framework_id' => json_encode($request->framework_id),
            'link' => $request->link,
        ]);

        return redirect()->route('projek.index')->with('success', 'Projek berhasil diperbarui dengan gambar WebP!');
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


    public function swapUrutanAjax(Request $request)
    {
        $current = Portofolio::findOrFail($request->id);
        $direction = $request->direction;

        if ($direction === 'up') {
            $neighbor = Portofolio::where('urutan', '<', $current->urutan)
                ->orderBy('urutan', 'desc')->first();
        } else {
            $neighbor = Portofolio::where('urutan', '>', $current->urutan)
                ->orderBy('urutan', 'asc')->first();
        }

        if ($neighbor) {
            $temp = $current->urutan;
            $current->urutan = $neighbor->urutan;
            $neighbor->urutan = $temp;

            $current->save();
            $neighbor->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Tidak bisa tukar urutan.']);
    }
}
