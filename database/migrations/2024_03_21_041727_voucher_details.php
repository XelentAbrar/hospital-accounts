<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voucher_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->nullable()->references('id')->on('vouchers')->onDelete('cascade');
            $table->foreignId('acc_code')->nullable()->references('id')->on('chart_of_accounts')->onDelete('cascade');
            $table->string('dr')->nullable();
            $table->string('cr')->nullable();
            $table->longText('transaction_type')->nullable();
            $table->longText('transaction_no')->nullable();
            $table->longText('remarks', 1000)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher_details');
    }
};
