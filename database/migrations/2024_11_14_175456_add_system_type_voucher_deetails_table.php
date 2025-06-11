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
        Schema::table('voucher_details', function (Blueprint $table) {
            $table->longText("system_type")->nullable();
        });

        Schema::table('voucher_audit_details', function (Blueprint $table) {
            $table->longText("system_type")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voucher_details', function (Blueprint $table) {
            $table->dropColumn('system_type');
        });
        Schema::table('voucher_audit_details', function (Blueprint $table) {
            $table->dropColumn('system_type');
        });
    }
};
