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
        Schema::create('giao_diches', function (Blueprint $table) {
            $table->id();
            $table->integer('id_Khach_hang');
            $table->string('ma_giao_dich');
            $table->string('orderInfo')->nullable();
            $table->string('transactionNo')->nullable();
            $table->string('paymentType')->nullable();
            $table->string('responseCode')->nullable();
            $table->string('transactionStatus')->nullable();
            $table->string('tinh_trang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giao_diches');
    }
};
