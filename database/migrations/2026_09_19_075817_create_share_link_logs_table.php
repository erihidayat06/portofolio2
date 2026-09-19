<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('share_link_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('share_link_id')->constrained('share_links')->onDelete('cascade');
            $table->date('date'); // Tanggal rekap (Contoh: 2026-09-19)
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('completed')->default(0);
            $table->timestamps();

            // Mencegah duplikasi tanggal untuk link yang sama
            $table->unique(['share_link_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('share_link_logs');
    }
};
