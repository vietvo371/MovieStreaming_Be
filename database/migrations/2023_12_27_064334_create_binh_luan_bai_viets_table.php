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
        Schema::create('binh_luan_bai_viets', function (Blueprint $table) {
            $table->id();
            $table->longText('noi_dung');
            $table->integer('id_bai_viet');
            $table->integer('id_khach_hang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('binh_luan_bai_viets');
    }
};
