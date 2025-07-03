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
        Schema::create('voucher_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_type_id')->nullable()->references('id')->on('voucher_types')->onDelete('cascade');
            $table->date('voucher_date');
            $table->string('type')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->string('narration')->nullable();
            $table->tinyInteger('is_posted')->default('0');
            $table->foreignId('created_by')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });


        Schema::create('voucher_audit_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_audit_id')->nullable()->references('id')->on('voucher_audits')->onDelete('cascade');
            $table->foreignId('acc_code')->nullable()->references('id')->on('chart_of_accounts')->onDelete('cascade');
            $table->decimal('dr', 11,2)->default(0);
            $table->decimal('cr', 11,2)->default(0);
            $table->string('transaction_type')->nullable();
            $table->string('transaction_no')->nullable();
            $table->text('remarks', 1000)->nullable();
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
        Schema::dropIfExists('voucher_audit_details');
        Schema::dropIfExists('voucher_audit');
    }
};
