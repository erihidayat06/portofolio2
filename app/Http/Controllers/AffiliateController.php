<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAffiliateRequest;
use App\Http\Requests\UpdateAffiliateRequest;

class AffiliateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Affiliate::latest()->get();
        return view('admin.affiliate.index',  compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.affiliate.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([

            'link' => 'required|string|max:255',
        ]);



        Affiliate::create([
            'link' => $request->link,
        ]);

        return redirect()->route('affiliate.index')->with('success', 'Link berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Affiliate $affiliate) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Affiliate $affiliate)
    {
        return view('admin.affiliate.edit',  compact('affiliate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Affiliate $affiliate)
    {
        // Validasi input
        $request->validate([

            'link' => 'required|string|max:255',
        ]);



        Affiliate::where('id', $affiliate->id)->update([
            'link' => $request->link,
        ]);

        return redirect()->route('affiliate.index')->with('success', 'Link berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Affiliate $affiliate)
    {
        $affiliate->delete();

        return redirect()->route('affiliate.index')->with('success', 'Link berhasil dihapus.');
    }
}
