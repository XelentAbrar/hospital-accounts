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
        if(file_exists(base_path('config/hrms.php'))) {
            Schema::table('departments', function (Blueprint $table) {
                $table->bigInteger('coa_id')->nullable();
            });
            Schema::table('categories', function (Blueprint $table) {
                $table->bigInteger('coa_id')->nullable();
            });
            Schema::table('employees', function (Blueprint $table) {
                $table->bigInteger('coa_id')->nullable();
            });
        }
        if(file_exists(base_path('config/donation.php'))) {
            Schema::table('donors', function (Blueprint $table) {
                $table->bigInteger('coa_id')->nullable();
            });
        }
        if(file_exists(base_path('config/expense.php'))) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->bigInteger('coa_id')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(file_exists(base_path('config/hrms.php'))) {
            Schema::table('departments', function (Blueprint $table) {
                $table->dropColumn('coa_id');
            });
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('coa_id');
            });
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('coa_id');
            });
        }
        if(file_exists(base_path('config/donation.php'))) {
            Schema::table('donors', function (Blueprint $table) {
                $table->dropColumn('coa_id');
            });
        }
        if(file_exists(base_path('config/expense.php'))) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->dropColumn('coa_id');
            });
        }
    }
};
