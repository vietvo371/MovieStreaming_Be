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
        Schema::create('phims', function (Blueprint $table) {
            $table->id();
            $table->string('ten_phim');
            $table->longText('hinh_anh');
            $table->longText('mo_ta');
            $table->integer('id_loai_phim');
            $table->integer('id_the_loai');
            $table->string('url');
            $table->integer('tinh_trang_yeu_thich');
            $table->integer('tinh_trang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phims');
    }
};
