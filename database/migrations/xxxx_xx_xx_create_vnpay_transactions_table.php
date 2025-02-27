<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVnpayTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('vnpay_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('vnp_amount', 10, 2);
            $table->string('vnp_txnref');
            $table->string('vnp_orderinfo');
            $table->string('vnp_response_code');
            $table->string('vnp_transaction_no');
            $table->string('vnp_bank_code');
            $table->string('vnp_payment_type');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vnpay_transactions');
    }
}
