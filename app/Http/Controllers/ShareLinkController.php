<?php

namespace App\Http\Controllers;

use App\Models\ShareLink;
use App\Models\ShareLinkLog;
use App\Models\Affiliate;
use App\Http\Requests\UpdateShareLinkRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ShareLinkController extends Controller
{
    /**
     * Display a listing of the resource (Include Rekap Harian, Bulanan, Tahunan).
     */
    public function index()
    {
        $now = Carbon::now();
        $todayStr = $now->toDateString();

        // Ambil data share link beserta log-nya
        $data = ShareLink::with('logs')->latest()->get()->map(function ($link) use ($now, $todayStr) {

            // 1. Hari Ini
            $link->views_today = $link->logs->where('date', $todayStr)->sum('views');
            $link->completed_today = $link->logs->where('date', $todayStr)->sum('completed');

            // 2. Bulan Ini
            $link->views_month = $link->logs->filter(function ($log) use ($now) {
                $logDate = Carbon::parse($log->date);
                return $logDate->month === $now->month && $logDate->year === $now->year;
            })->sum('views');

            $link->completed_month = $link->logs->filter(function ($log) use ($now) {
                $logDate = Carbon::parse($log->date);
                return $logDate->month === $now->month && $logDate->year === $now->year;
            })->sum('completed');

            // 3. Tahun Ini
            $link->views_year = $link->logs->filter(function ($log) use ($now) {
                return Carbon::parse($log->date)->year === $now->year;
            })->sum('views');

            $link->completed_year = $link->logs->filter(function ($log) use ($now) {
                return Carbon::parse($log->date)->year === $now->year;
            })->sum('completed');

            // 4. Total Keseluruhan
            $link->total_views = $link->logs->sum('views');
            $link->total_completed = $link->logs->sum('completed');

            return $link;
        });

        // Data Grafik 7 Hari Terakhir
        $chartDays = [];
        $chartViews = [];
        $chartCompleted = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->toDateString();

            $chartDays[] = $date->format('d M');
            $chartViews[] = ShareLinkLog::where('date', $dateStr)->sum('views');
            $chartCompleted[] = ShareLinkLog::where('date', $dateStr)->sum('completed');
        }



        return view('admin.shareLink.index', compact('data', 'chartDays', 'chartViews', 'chartCompleted'));
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
        $request->validate([
            'nama' => 'required|string|max:255',
            'link' => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->nama) . '-' . now()->format('YmdHis');

        ShareLink::create([
            'nama' => $request->nama,
            'link' => $request->link,
            'slug' => $slug
        ]);

        return redirect()->route('share-link.index')->with('success', 'Link berhasil ditambahkan.');
    }

    /**
     * Display the specified resource & Log Views Harian.
     */
    public function show($slug)
    {
        $shareLink = ShareLink::where('slug', $slug)->firstOrFail();
        $today = Carbon::today()->toDateString();

        // 1. Increment views terlebih dahulu
        ShareLinkLog::updateOrCreate(
            [
                'share_link_id' => $shareLink->id,
                'date'          => $today,
            ]
        )->increment('views');

        // 2. Ambil ulang data shareLink beserta sum log yang sudah ter-update
        $shareLink->loadSum('logs as views_count', 'views')
            ->loadSum('logs as completed_count', 'completed');

        $affiliate = Affiliate::first();

        // 3. Ambil link lainnya dengan Pagination (5 data per halaman)
        $otherLinks = ShareLink::where('id', '!=', $shareLink->id)
            ->withSum('logs as completed_count', 'completed')
            ->latest()
            ->paginate(10); // Menggunakan pagination

        return view('admin.showShare.index', compact('shareLink', 'affiliate', 'otherLinks'));
    }
    /**
     * Log Complete Harian saat tombol diklik.
     */
    public function trackComplete($id)
    {
        $today = Carbon::today()->toDateString();

        // Increment Completed Harian di ShareLinkLog
        $log = ShareLinkLog::firstOrCreate(
            [
                'share_link_id' => $id,
                'date'          => $today,
            ],
            [
                'views'     => 0,
                'completed' => 0,
            ]
        );

        $log->increment('completed');

        return response()->json(['status' => 'success']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShareLink $shareLink)
    {
        return view('admin.shareLink.edit', compact('shareLink'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShareLink $shareLink)
    {
        $validateData = $request->validate([
            'nama' => 'required|string|max:255',
            'link' => 'required|string|max:255',
        ]);

        $shareLink->update($validateData);

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
