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
        Schema::table('share_links', function (Blueprint $table) {
            $table->unsignedBigInteger('clicks')->default(0)->after('link');
            $table->unsignedBigInteger('completed')->default(0)->after('clicks');
        });
    }

    public function down(): void
    {
        Schema::table('share_links', function (Blueprint $table) {
            $table->dropColumn(['clicks', 'completed']);
        });
    }
};
