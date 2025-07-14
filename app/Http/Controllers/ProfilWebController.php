<?php

namespace App\Http\Controllers;

use App\Models\ProfilWeb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProfilWebRequest;
use App\Http\Requests\UpdateProfilWebRequest;

class ProfilWebController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profilWeb = ProfilWeb::firstOrCreate([], [
            'judul' => 'Judul Default',
            'deskripsi' => 'Deskripsi default',
            'cv' => null,
            'sertifikat' => null,
            'deskripsi_profil' => 'Deskripsi profil default',
            'instagram' => null,
            'youtube' => null,
            'tiktok' => null,
        ]);

        return view('admin.dashboard', compact('profilWeb'));
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $profilWeb = ProfilWeb::findOrFail($id);

        $data = $request->validate([
            'judul' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'cv' => 'nullable|mimes:pdf|max:8048', // max 2MB, hanya PDF
            'sertifikat' => 'nullable|url',
            'deskripsi_profil' => 'nullable|string',
            'instagram' => 'nullable|string',
            'youtube' => 'nullable|string',
            'tiktok' => 'nullable|string',
        ]);

        // Jika ada file baru di-upload
        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $path = $file->store('cv', 'public'); // simpan di storage/app/public/cv
            $data['cv'] = $path;

            // Opsional: hapus file lama jika perlu
            if ($profilWeb->cv) {
                Storage::disk('public')->delete($profilWeb->cv);
            }
        }

        $profilWeb->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
