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
        Schema::table('phims', function (Blueprint $table) {
            $table->fullText(['ten_phim', 'mo_ta'], 'search_phim_content');
            $table->index('id_loai_phim', 'idx_phim_loai');
            $table->index('tinh_trang', 'idx_phim_status');
            $table->index(['tinh_trang', 'id_loai_phim'], 'idx_phim_filter_combo');
        });
        Schema::table('chi_tiet_the_loais', function (Blueprint $table) {
            $table->index('id_the_loai', 'idx_chitiet_theloai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phims', function (Blueprint $table) {
            $table->dropFullText('search_phim_content');
            $table->dropIndex('idx_phim_loai');
            $table->dropIndex('idx_phim_status');
            $table->dropIndex('idx_phim_filter_combo');
        });
        Schema::table('chi_tiet_the_loais', function (Blueprint $table) {
            $table->dropIndex('idx_chitiet_theloai');
        });
    }
};
