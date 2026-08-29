<?php

namespace App\Http\Controllers;

use App\Models\ShareLink;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateShareLinkRequest;
use App\Models\Affiliate;

class ShareLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ShareLink::latest()->get();
        return view('admin.shareLink.index',  compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.shareLink.create');
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'link' => 'required|string|max:255',
        ]);

        // Buat slug dari nama + timestamp
        $slug = Str::slug($request->nama) . '-' . now()->format('YmdHis');

        ShareLink::create([
            'nama' => $request->nama,
            'link' => $request->link,
            'slug' => $slug
        ]);

        return redirect()->route('share-link.index')->with('success', 'Link berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $shareLink = ShareLink::where('slug', $slug)->firstOrFail();

        // Increment total pengunjung halaman unlock
        $shareLink->increment('clicks');

        $affiliate = Affiliate::first(); // Sesuaikan dengan logika affiliate Anda

        return view('admin.showShare.index', compact('shareLink', 'affiliate'));
    }

    public function trackComplete($id)
    {
        $shareLink = ShareLink::findOrFail($id);
        $shareLink->increment('completed');

        return response()->json(['status' => 'success']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShareLink $shareLink)
    {


        return view('admin.shareLink.edit',  compact('shareLink'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShareLink $shareLink)
    {
        // Validasi input
        $validateData =  $request->validate([
            'nama' => 'required|string|max:255',
            'link' => 'required|string|max:255',
        ]);


        ShareLink::where('id', $shareLink->id)->update($validateData);

        return redirect()->route('share-link.index')->with('success', 'Link berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShareLink $shareLink)
    {
        $shareLink->delete();

        return back()->with('success', 'Link berhasil dihapus.');
    }
}
