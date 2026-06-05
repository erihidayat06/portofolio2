<?php

namespace App\Http\Controllers;

use App\Models\Bahasa;
use App\Models\Framework;
use App\Models\Portofolio;
use App\Models\ProfilWeb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function chatWithAI(Request $request)
    {
        try {
            $userQuestion = $request->input('message');
            $apiKey = env('GEMINI_API_KEY');

            // Ambil data (Gunakan first() atau toArray() agar data bersih)
            $profil = ProfilWeb::first()?->deskripsi_profil ?? 'Data tidak tersedia';
            $framework = Framework::pluck('nama')->implode(', ');
            $bahasa = Bahasa::pluck('nama')->implode(', ');
            $projek = Portofolio::all()->map(fn($p) => "{$p->nm_projek} ({$p->deskripsi})")->implode('; ');

            $context = "Anda adalah asisten AI untuk portofolio Eri Hidayat.
                    Profil: {$profil}.
                    Framework: {$framework}.
                    Bahasa: {$bahasa}.
                    Projek: {$projek}.
                    Jawab pertanyaan: {$userQuestion}";

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [['parts' => [['text' => $context]]]]
            ]);

            // Cek jika API gagal
            if ($response->failed()) {
                // CATAT ERROR KE LARAVEL LOG
                Log::error('Gemini API Error: ' . $response->body());

                return response()->json(['reply' => 'Gagal terhubung ke AI. Cek log server.'], 500);
            }

            $reply = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'AI tidak memberikan jawaban.';
            return response()->json(['reply' => $reply]);
        } catch (\Exception $e) {
            // CATAT ERROR JIKA TERJADI EXCEPTION
            Log::error('Chat Error: ' . $e->getMessage());
            return response()->json(['reply' => 'Terjadi kesalahan sistem.'], 500);
        }
    }
}
