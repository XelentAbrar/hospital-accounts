<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use XelentAbrar\HospitalAccounts\Models\Accounts\ControlAccount;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        ControlAccount::truncate();
        Schema::enableForeignKeyConstraints();

        $controlAccounts = [
            ['acc_code' => '100', 'acc_desc' => 'Cash', 'created_at' => now(), 'updated_at' => now()],
            ['acc_code' => '110', 'acc_desc' => 'Bank Accounts', 'created_at' => now(), 'updated_at' => now()],
            ['acc_code' => '120', 'acc_desc' => 'Accounts Receivable', 'created_at' => now(), 'updated_at' => now()],
            ['acc_code' => '130', 'acc_desc' => 'Accounts Payable', 'created_at' => now(), 'updated_at' => now()],
            ['acc_code' => '140', 'acc_desc' => 'Inventory', 'created_at' => now(), 'updated_at' => now()],
            ['acc_code' => '150', 'acc_desc' => 'Fixed Assets', 'created_at' => now(), 'updated_at' => now()],
            ['acc_code' => '160', 'acc_desc' => 'Payroll', 'created_at' => now(), 'updated_at' => now()],
            ['acc_code' => '170', 'acc_desc' => 'Revenue', 'created_at' => now(), 'updated_at' => now()],
            ['acc_code' => '180', 'acc_desc' => 'Expenses', 'created_at' => now(), 'updated_at' => now()],
            ['acc_code' => '190', 'acc_desc' => 'Tax Liabilities', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('control_accounts')->insert($controlAccounts);
    }

};
